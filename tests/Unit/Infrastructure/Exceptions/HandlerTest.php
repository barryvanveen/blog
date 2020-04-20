<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Exceptions;

use App\Application\Http\Exceptions\ForbiddenHttpException;
use App\Application\Http\Exceptions\InternalServerErrorHttpException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\Exceptions\PageExpiredHttpException;
use App\Application\Http\StatusCode;
use App\Infrastructure\Exceptions\Handler;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;
use Tests\TestCase;
use Throwable;
use Validator;

/**
 * @covers \App\Infrastructure\Exceptions\Handler
 * @covers \App\Application\Http\Exceptions\ForbiddenHttpException
 * @covers \App\Application\Http\Exceptions\InternalServerErrorHttpException
 * @covers \App\Application\Http\Exceptions\NotFoundHttpException
 * @covers \App\Application\Http\Exceptions\PageExpiredHttpException
 */
class HandlerTest extends TestCase
{
    private const REDIRECT_TO_PATH = '/my/redirect/to/path';

    private const PATH_TO_LOGIN = '/path/to/login';

    /** @var ObjectProphecy|LoggerInterface */
    private $logger;

    /** @var ObjectProphecy|Factory */
    private $viewFactory;

    /** @var ObjectProphecy|Redirector */
    private $redirector;

    /** @var ObjectProphecy|UrlGenerator */
    private $urlGenerator;

    /** @var ObjectProphecy|Request */
    private $request;

    /** @var Handler */
    private $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->viewFactory = $this->prophesize(Factory::class);
        $this->redirector = $this->prophesize(Redirector::class);
        $this->urlGenerator = $this->prophesize(UrlGenerator::class);
        $this->request = $this->prophesize(Request::class);

        $this->urlGenerator->route('login')->willReturn(self::PATH_TO_LOGIN);

        $this->handler = new Handler(
            $this->logger->reveal(),
            $this->viewFactory->reveal(),
            $this->redirector->reveal(),
            $this->urlGenerator->reveal()
        );
    }

    /**
     * @test
     *
     * @dataProvider shouldLogExceptionsDataProvider
     */
    public function itReportsExceptionsThatShouldBeReported(
        Throwable $exception,
        bool $shouldBeReported
    ): void {
        // act
        $this->handler->report($exception);

        // assert
        if ($shouldBeReported) {
            $this->logger->error('MyMessage', ['exception' => $exception])
                ->shouldBeCalled();
        } else {
            $this->logger->error('MyMessage', ['exception' => $exception])
                ->shouldNotBeCalled();
        }
    }

    public function shouldLogExceptionsDataProvider(): array
    {
        return [
            [
                'exception' => new NotFoundHttpException('MyMessage', StatusCode::STATUS_NOT_FOUND),
                'shouldBeReported' => false,
            ],
            [
                'exception' => new SuspiciousOperationException('MyMessage', StatusCode::STATUS_NOT_FOUND),
                'shouldBeReported' => true,
            ],
            [
                'exception' => new Exception('MyMessage', StatusCode::STATUS_NOT_FOUND),
                'shouldBeReported' => true,
            ],
        ];
    }

    /** @test */
    public function itRendersExceptions(): void
    {
        // arrange
        $exception = new NotFoundHttpException('MyMessage', StatusCode::STATUS_NOT_FOUND);

        $this->viewFactory->make('errors.404', Argument::type('array'))
            ->willReturn('myViewContent');

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('myViewContent', $response->getContent());
        $this->assertEquals(StatusCode::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @dataProvider frameworkExceptionMappingDataProvider
     */
    public function itMapsFrameworkExceptionsIntoHttpExceptions(
        Throwable $frameworkException,
        string $httpExceptionClass,
        int $httpStatusCode
    ): void {
        // arrange
        $this->viewFactory->make(Argument::cetera())
            ->willReturn('myViewContent');

        // act
        $response = $this->handler->render($this->request->reveal(), $frameworkException);

        // assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf($httpExceptionClass, $response->exception);
        $this->assertEquals($httpStatusCode, $response->getStatusCode());
    }

    public function frameworkExceptionMappingDataProvider(): array
    {
        return [
            [
                'frameworkException' => new AuthorizationException(),
                'httpExceptionClass' => ForbiddenHttpException::class,
                'httpStatusCode' => StatusCode::STATUS_FORBIDDEN,
            ],
            [
                'frameworkException' => new TokenMismatchException(),
                'httpExceptionClass' => PageExpiredHttpException::class,
                'httpStatusCode' => StatusCode::STATUS_PAGE_EXPIRED,
            ],
            [
                'frameworkException' => new SymfonyNotFoundHttpException(),
                'httpExceptionClass' => NotFoundHttpException::class,
                'httpStatusCode' => StatusCode::STATUS_NOT_FOUND,
            ],
            [
                'frameworkException' => new SuspiciousOperationException(),
                'httpExceptionClass' => NotFoundHttpException::class,
                'httpStatusCode' => StatusCode::STATUS_NOT_FOUND,
            ],
        ];
    }

    /** @test */
    public function itTurnsNonHttpExceptionsIntoInternalServerErrorExceptions(): void
    {
        // arrange
        $this->viewFactory->make('errors.500', Argument::type('array'))
            ->willReturn('myViewContent');

        // act
        $response = $this->handler->render($this->request->reveal(), new Exception());

        // assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(InternalServerErrorHttpException::class, $response->exception);
        $this->assertEquals(StatusCode::STATUS_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    /** @test */
    public function itRedirectsToTheLoginRouteOnAuthenticationExceptions(): void
    {
        // arrange
        $exception = new AuthenticationException();

        $this->redirector->guest(self::PATH_TO_LOGIN)
            ->willReturn(new RedirectResponse(self::PATH_TO_LOGIN))
            ->shouldBeCalled();

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /** @test */
    public function itRedirectsOnValidationExceptions(): void
    {
        $input = [
            'email' => 123,
            'password' => 'secret',
        ];

        $rules = [
            'email' => 'string',
        ];

        $validator = Validator::make($input, $rules, [
            'email.string' => 'Email message',
        ]);

        $exception = (new ValidationException($validator))
            ->errorBag('default')
            ->redirectTo(self::REDIRECT_TO_PATH);

        $this->request->input()->willReturn($input);

        /** @var ObjectProphecy|Store $session */
        $session = $this->prophesize(Store::class);
        $session->get('errors', Argument::type(ViewErrorBag::class))->shouldBeCalled();

        $redirect = new RedirectResponse(self::REDIRECT_TO_PATH);
        $redirect->setSession($session->reveal());

        $this->redirector->to(self::REDIRECT_TO_PATH)
            ->willReturn($redirect)
            ->shouldBeCalled();

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // inputs are flashed by password is removed
        $session->flashInput(Argument::that(function (array $input) {
            return $input['email'] = 123 && !isset($input['password']);
        }))->shouldBeCalled();

        // errors are flashed
        $session->flash('errors', Argument::that(function (ViewErrorBag $errorBag) {
            return ($errorBag->first('email') === 'Email message');
        }))->shouldBeCalled();
    }
}
