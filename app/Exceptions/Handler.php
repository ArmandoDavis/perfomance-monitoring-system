<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception) && !$request->expectsJson()) {
            $status = $exception->getStatusCode();

            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [
                    'exception' => $exception
                ], $status);
            }

            if (view()->exists("errors.default")) {
                return response()->view("errors.default", [
                    'exception' => $exception
                ], $status);
            }
        }

        return parent::render($request, $exception);
    }
}
