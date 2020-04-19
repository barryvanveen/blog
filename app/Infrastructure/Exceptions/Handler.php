<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\Http\Exceptions\ForbiddenHttpException;
use App\Application\Http\Exceptions\HttpExceptionInterface;
use App\Application\Http\Exceptions\InternalServerErrorHttpException;
use App\Application\Http\Exceptions\NotFoundHttpException;
use App\Application\Http\Exceptions\PageExpiredHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Exceptions\WhoopsHandler;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;
use Throwable;
use Whoops\Handler\HandlerInterface;
use Whoops\Run as Whoops;

final class Handler implements ExceptionHandlerContract
{
    /** @var string[] */
    private $dontReport = [
        NotFoundHttpException::class,
        SymfonyNotFoundHttpException::class,
    ];

    /** @var string[] */
    private $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /** @var LoggerInterface */
    private $logger;

    /** @var Factory */
    private $viewFactory;

    /** @var Redirector */
    private $redirector;

    /** @var UrlGenerator */
    private $urlGenerator;

    public function __construct(
        LoggerInterface $logger,
        Factory $viewFactory,
        Redirector $redirector,
        UrlGenerator $urlGenerator
    ) {
        $this->logger = $logger;
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
        $this->urlGenerator = $urlGenerator;
    }

    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception) === false) {
            return;
        }

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);
    }

    public function shouldReport(Throwable $exception)
    {
        $matches = array_filter($this->dontReport, function (string $type) use ($exception) {
            return $exception instanceof $type;
        });

        return count($matches) === 0;
    }

    public function render($request, Throwable $exception): SymfonyResponse
    {
        $exception = $this->mapFrameworkExceptionToHttpException($exception);

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated();
        } elseif ($exception instanceof ValidationException) {
            return $this->invalid($request, $exception);
        }

        return $this->prepareResponse($exception);
    }

    private function mapFrameworkExceptionToHttpException(Throwable $exception): Throwable
    {
        if ($exception instanceof AuthorizationException) {
            $exception = ForbiddenHttpException::create($exception);
        } elseif ($exception instanceof TokenMismatchException) {
            $exception = PageExpiredHttpException::create($exception);
        } elseif ($exception instanceof SymfonyNotFoundHttpException) {
            $exception = NotFoundHttpException::create($exception);
        } elseif ($exception instanceof SuspiciousOperationException) {
            $exception = NotFoundHttpException::create($exception);
        }

        return $exception;
    }

    private function unauthenticated(): RedirectResponse
    {
        return $this->redirector->guest($this->urlGenerator->route('login'));
    }

    private function invalid(Request $request, ValidationException $exception): RedirectResponse
    {
        return $this->redirector->to($exception->redirectTo)
            ->withInput(Arr::except($request->input(), $this->dontFlash))
            ->withErrors($exception->errors(), $exception->errorBag);
    }

    private function prepareResponse(Throwable $exception): SymfonyResponse
    {
        // debug mode -> show exception details
        if ($this->isHttpException($exception) === false && config('app.debug')) {
            return $this->toDebugResponse($exception);
        }

        // convert non-http exceptions to 500 Internal Server Error
        if ($this->isHttpException($exception) === false) {
            $exception = InternalServerErrorHttpException::create($exception);
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
        return SymfonyResponse::create($this->getWhoopsOutput($exception), 500);
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
            return (new WhoopsHandler)->forDebug();
        }
    }

    private function toResponse(Throwable $exception): Response
    {
        $view = $this->viewFactory->make('errors.'.$exception->getCode(), [
            'errors' => new ViewErrorBag(),
            'exception' => $exception,
        ]);

        $response = new Response($view, (int) $exception->getCode());

        return $response->withException($exception);
    }

    public function renderForConsole($output, Throwable $e): void
    {
        (new ConsoleApplication)->renderThrowable($e, $output);
    }
}
