<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\Http\Exceptions\ForbiddenHttpException;
use App\Application\Http\Exceptions\HttpExceptionInterface;
use App\Application\Http\Exceptions\InternalServerErrorHttpException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\Exceptions\PageExpiredHttpException;
use App\Application\Http\Exceptions\ServiceUnavailableException;
use App\Application\Http\Exceptions\TooManyRequestsHttpException;
use App\Application\Http\StatusCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;
use Throwable;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Run as Whoops;

final class Handler implements ExceptionHandlerContract
{
    /** @var string[] */
    private array $dontReport = [
        NotFoundHttpException::class,
        SymfonyNotFoundHttpException::class,
        MaintenanceModeException::class,
        TokenMismatchException::class,
        ValidationException::class,
        MethodNotAllowedHttpException::class,
        AuthenticationException::class,
    ];

    /** @var string[] */
    private array $dontFlash = [
        'password',
        'password_confirmation',
    ];

    private LoggerInterface $logger;
    private Factory $viewFactory;
    private Redirector $redirector;
    private UrlGenerator $urlGenerator;
    private ResponseFactory $responseFactory;

    public function __construct(
        LoggerInterface $logger,
        Factory $viewFactory,
        Redirector $redirector,
        UrlGenerator $urlGenerator,
        ResponseFactory $responseFactory
    ) {
        $this->logger = $logger;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->urlGenerator = $urlGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function report(Throwable $e)
    {
        if ($this->shouldReport($e) === false) {
            return;
        }

        $this->logger->error($e->getMessage(), ['exception' => $e]);
    }

    public function shouldReport(Throwable $e)
    {
        $matches = array_filter($this->dontReport, function (string $type) use ($e) {
            return $e instanceof $type;
        });

        return count($matches) === 0;
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return SymfonyResponse
     */
    public function render($request, Throwable $e)
    {
        $exception = $this->mapFrameworkExceptionToHttpException($e);

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request);
        }

        if ($exception instanceof ValidationException) {
            return $this->invalid($request, $exception);
        }

        return $this->prepareResponse($request, $exception);
    }

    private function mapFrameworkExceptionToHttpException(Throwable $exception): Throwable
    {
        return match (get_class($exception)) {
            AuthorizationException::class => ForbiddenHttpException::create($exception),
            TokenMismatchException::class => PageExpiredHttpException::create($exception),
            SuspiciousOperationException::class, SymfonyNotFoundHttpException::class => NotFoundHttpException::create($exception),
            MaintenanceModeException::class => ServiceUnavailableException::create($exception),
            ThrottleRequestsException::class => TooManyRequestsHttpException::create($exception),
            default => $exception,
        };
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    private function unauthenticated(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->responseFactory->json(['message' => 'Forbidden'], StatusCode::STATUS_UNAUTHORIZED);
        }

        return $this->redirector->guest($this->urlGenerator->route('login'));
    }

    /**
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse|RedirectResponse
     */
    private function invalid(Request $request, ValidationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->responseFactory->json($exception->errors(), StatusCode::STATUS_BAD_REQUEST);
        }

        return $this->redirector->to($exception->redirectTo)
            ->withInput(Arr::except($request->input(), $this->dontFlash))
            ->withErrors($exception->errors(), $exception->errorBag);
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|SymfonyResponse
     */
    private function prepareResponse(Request $request, Throwable $exception)
    {
        // debug mode -> show exception details
        if ($this->isHttpException($exception) === false && config('app.debug')) {
            return $this->toDebugResponse($exception);
        }

        // convert non-http exceptions to 500 Internal Server Error
        if ($this->isHttpException($exception) === false) {
            $exception = InternalServerErrorHttpException::create($exception);
        }

        // return an error message for json requests
        if ($request->expectsJson()) {
            return $this->toJsonResponse($exception);
        }

        // display error page
        return $this->toResponse($exception);
    }

    private function isHttpException(Throwable $e): bool
    {
        return $e instanceof HttpExceptionInterface;
    }

    private function toDebugResponse(Throwable $exception): SymfonyResponse
    {
        return new SymfonyResponse($this->getWhoopsOutput($exception), StatusCode::STATUS_INTERNAL_SERVER_ERROR);
    }

    private function getWhoopsOutput(Throwable $exception): string
    {
        return tap(new Whoops, function (Whoops $whoops) {
            $whoops->appendHandler($this->whoopsHandler());

            $whoops->writeToOutput(false);

            $whoops->allowQuit(false);
        })->handleException($exception);
    }

    protected function whoopsHandler(): HandlerInterface
    {
        try {
            return app(HandlerInterface::class);
        } catch (BindingResolutionException $e) {
            return new PlainTextHandler();
        }
    }

    private function toResponse(Throwable $exception): Response
    {
        $view = $this->viewFactory->make('errors.'.$exception->getCode(), [
            'errors' => new ViewErrorBag(),
            'exception' => $exception,
        ]);

        return (new Response($view, (int)$exception->getCode()))->withException($exception);
    }

    private function toJsonResponse(Throwable $exception): JsonResponse
    {
        if ($exception instanceof PageExpiredHttpException) {
            return $this->responseFactory->json(
                ['error' => 'Your token expired, please reload the page'],
                StatusCode::STATUS_PAGE_EXPIRED
            );
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return $this->responseFactory->json(
                ['error' => 'Too many requests, please try again later'],
                StatusCode::STATUS_TOO_MANY_REQUESTS
            );
        }

        return $this->responseFactory->json(
            ['error' => 'Sorry, something went wrong'],
            StatusCode::STATUS_INTERNAL_SERVER_ERROR
        );
    }

    public function renderForConsole($output, Throwable $e): void
    {
        (new ConsoleApplication)->renderThrowable($e, $output);
    }
}
