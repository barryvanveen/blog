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
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
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

    public function report(Throwable $e)
    {
        if ($this->shouldReport($e) === false) {
            return;
        }

        Log::error($e->getMessage(), ['exception' => $e]);
    }

    public function shouldReport(Throwable $e)
    {
        $matches = array_filter($this->dontReport, fn(string $type) => $e instanceof $type);

        return count($matches) === 0;
    }

    /**
     * @param Request $request
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
        return match ($exception::class) {
            AuthorizationException::class => ForbiddenHttpException::create($exception),
            TokenMismatchException::class => PageExpiredHttpException::create($exception),
            SuspiciousOperationException::class, SymfonyNotFoundHttpException::class => NotFoundHttpException::create($exception),
            MaintenanceModeException::class => ServiceUnavailableException::create($exception),
            ThrottleRequestsException::class => TooManyRequestsHttpException::create($exception),
            default => $exception,
        };
    }

    private function unauthenticated(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return ResponseFactory::json(['message' => 'Forbidden'], StatusCode::STATUS_UNAUTHORIZED);
        }

        return Redirect::guest(URL::route('login'));
    }

    private function invalid(Request $request, ValidationException $exception): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return ResponseFactory::json($exception->errors(), StatusCode::STATUS_BAD_REQUEST);
        }

        return Redirect::to($exception->redirectTo)
            ->withInput(Arr::except($request->input(), $this->dontFlash))
            ->withErrors($exception->errors(), $exception->errorBag);
    }

    /**
     * @return JsonResponse|SymfonyResponse
     */
    private function prepareResponse(Request $request, Throwable $exception): JsonResponse|SymfonyResponse
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
        } catch (BindingResolutionException) {
            return new PlainTextHandler();
        }
    }

    private function toResponse(Throwable $exception): Response
    {
        $view = View::make('errors.'.$exception->getCode(), [
            'errors' => new ViewErrorBag(),
            'exception' => $exception,
        ]);

        return (new Response($view, (int)$exception->getCode()))->withException($exception);
    }

    private function toJsonResponse(Throwable $exception): JsonResponse
    {
        if ($exception instanceof PageExpiredHttpException) {
            return ResponseFactory::json(
                ['error' => 'Your token expired, please reload the page'],
                StatusCode::STATUS_PAGE_EXPIRED
            );
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return ResponseFactory::json(
                ['error' => 'Too many requests, please try again later'],
                StatusCode::STATUS_TOO_MANY_REQUESTS
            );
        }

        return ResponseFactory::json(
            ['error' => 'Sorry, something went wrong'],
            StatusCode::STATUS_INTERNAL_SERVER_ERROR
        );
    }

    public function renderForConsole($output, Throwable $e): void
    {
        (new ConsoleApplication)->renderThrowable($e, $output);
    }
}
