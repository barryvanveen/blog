<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Exceptions;

use App\Application\Http\Exceptions\ForbiddenHttpException;
use App\Application\Http\Exceptions\InternalServerErrorHttpException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\Exceptions\PageExpiredHttpException;
use App\Application\Http\Exceptions\ServiceUnavailableException;
use App\Application\Http\Exceptions\TooManyRequestsHttpException;
use App\Application\Http\StatusCode;
use App\Infrastructure\Exceptions\Handler;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Mockery;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
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
 * @covers \App\Application\Http\Exceptions\TooManyRequestsHttpException
 */
class HandlerTest extends TestCase
{
    private const REDIRECT_TO_PATH = '/my/redirect/to/path';
    private const PATH_TO_LOGIN = '/path/to/login';
    private const VIEW_CONTENT = 'myViewContent';

    private ObjectProphecy|Request $request;
    private Handler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->prophesize(Request::class);
        $this->request->expectsJson()->willReturn(false);

        Log::spy();
        View::spy();
        Redirect::spy();
        URL::spy();
        ResponseFactory::spy();

        $this->handler = new Handler();
    }

    /**
     * @test
     *
     * @dataProvider shouldLogExceptionsDataProvider
     */
    public function itReportsExceptionsThatShouldBeReported(
        Throwable $exception,
        bool $shouldBeReported,
    ): void {
        // act
        $this->handler->report($exception);

        // assert
        if ($shouldBeReported) {
            Log::shouldHaveReceived()->error('MyMessage', ['exception' => $exception]);
        } else {
            Log::shouldNotHaveReceived()->error('MyMessage', ['exception' => $exception]);
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

        View::shouldReceive('make')
            ->with('errors.404', Mockery::type('array'))
            ->andReturn(self::VIEW_CONTENT);

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(self::VIEW_CONTENT, $response->getContent());
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
        int $httpStatusCode,
    ): void {
        // arrange
        View::shouldReceive('make')
            ->with(Mockery::any())
            ->andReturn(self::VIEW_CONTENT);

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
            [
                'frameworkException' => new MaintenanceModeException(60, 60, "try again"),
                'httpExceptionClass' => ServiceUnavailableException::class,
                'httpStatusCode' => StatusCode::STATUS_SERVICE_UNAVAILABLE,
            ],
            [
                'frameworkException' => new ThrottleRequestsException(60),
                'httpExceptionClass' => TooManyRequestsHttpException::class,
                'httpStatusCode' => StatusCode::STATUS_TOO_MANY_REQUESTS,
            ],
        ];
    }

    /** @test */
    public function itTurnsNonHttpExceptionsIntoInternalServerErrorExceptions(): void
    {
        // arrange
        View::shouldReceive('make')
            ->with('errors.500', Mockery::type('array'))
            ->andReturn(self::VIEW_CONTENT);

        // act
        $response = $this->handler->render($this->request->reveal(), new Exception());

        // assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(InternalServerErrorHttpException::class, $response->exception);
        $this->assertEquals(StatusCode::STATUS_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    /** @test */
    public function itReturnsAJsonErrorMessageOnUnknownExceptions(): void
    {
        // arrange
        $exception = new Exception();

        $this->request->expectsJson()->willReturn(true);

        ResponseFactory::shouldReceive('json')
            ->with(Mockery::type('array'), StatusCode::STATUS_INTERNAL_SERVER_ERROR)
            ->andReturn(new JsonResponse());

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function itRedirectsToTheLoginRouteOnAuthenticationExceptions(): void
    {
        // arrange
        $exception = new AuthenticationException();

        Url::shouldReceive('route')
            ->with('login')
            ->andReturn(self::PATH_TO_LOGIN);

        Redirect::shouldReceive('guest')
            ->with(self::PATH_TO_LOGIN)
            ->andReturn(new RedirectResponse(self::PATH_TO_LOGIN));

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /** @test */
    public function itReturnsAJsonErrorMessageOnAuthenticationExceptions(): void
    {
        // arrange
        $exception = new AuthenticationException();

        $this->request->expectsJson()->willReturn(true);

        ResponseFactory::shouldReceive('json')
            ->with(Mockery::type('array'), StatusCode::STATUS_UNAUTHORIZED)
            ->andReturn(new JsonResponse());

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(JsonResponse::class, $response);
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

        Redirect::shouldReceive('to')
            ->with(self::REDIRECT_TO_PATH)
            ->andReturn($redirect);

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // inputs are flashed by password is removed
        $session->flashInput(Argument::that(fn(array $input) => ($input['email'] = 123) && !isset($input['password'])))->shouldBeCalled();

        // errors are flashed
        $session->flash('errors', Argument::that(fn(ViewErrorBag $errorBag) => $errorBag->first('email') === 'Email message'))->shouldBeCalled();
    }

    /** @test */
    public function itReturnsAJsonErrorMessageOnValidationExceptions(): void
    {
        // arrange
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

        $this->request->expectsJson()->willReturn(true);

        ResponseFactory::shouldReceive('json')
            ->with(Mockery::type('array'), StatusCode::STATUS_BAD_REQUEST)
            ->andReturn(new JsonResponse());

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function itReturnsAJsonErrorMessageOnTokenMismatchExceptions(): void
    {
        // arrange
        $exception = new TokenMismatchException();

        $this->request->expectsJson()->willReturn(true);

        ResponseFactory::shouldReceive('json')
            ->with(Mockery::type('array'), StatusCode::STATUS_PAGE_EXPIRED)
            ->andReturn(new JsonResponse());

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function itReturnsAJsonErrorMessageOnThrottleRequestsExceptions(): void
    {
        // arrange
        $exception = new ThrottleRequestsException();

        $this->request->expectsJson()->willReturn(true);

        ResponseFactory::shouldReceive('json')
            ->with(Mockery::type('array'), StatusCode::STATUS_TOO_MANY_REQUESTS)
            ->andReturn(new JsonResponse());

        // act
        $response = $this->handler->render($this->request->reveal(), $exception);

        // assert
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
