<?php

namespace Microwin7\PHPUtils\Exceptions\Handler;

use Microwin7\PHPUtils\Configs\MainConfig;
use Microwin7\PHPUtils\Response\JsonResponse;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Exceptions\UserNotFoundException;

class ExceptionHandler
{
    public function __construct()
    {
        if (MainConfig::SENTRY_ENABLE) \Sentry\init(['dsn' => MainConfig::SENTRY_DSN]);
        set_exception_handler(array($this, 'exception_handler'));
    }
    public function exception_handler(\Throwable $exception)
    {
        if ($exception instanceof UserNotFoundException) {
            JsonResponse::failed(code_response: $exception->getCode());
        }
        if ($exception instanceof HttpErrorException) {
            JsonResponse::failed(error: $exception->getMessage(), code: $exception->getCode());
        }
        if ($exception instanceof \Throwable) {
            if (MainConfig::SENTRY_ENABLE) \Sentry\captureException($exception);
            JsonResponse::failed(error: $exception->getMessage());
        }
    }
}
