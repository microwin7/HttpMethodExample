<?php

namespace Microwin7\PHPUtils\Exceptions\Handler;

use Gravita\Http\Exceptions\UserNotFound;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Configs\MainConfig;
use Gravita\Http\Exceptions\HttpErrorException;

class ExceptionHandler
{
    public function __construct()
    {
        if (MainConfig::SENTRY_ENABLE) \Sentry\init(['dsn' => MainConfig::SENTRY_DSN]);
        set_exception_handler(array($this, 'exception_handler'));
    }
    public function exception_handler(\Throwable $exception)
    {
        if ($exception instanceof UserNotFound) {
            REsponse::failed(code_response: $exception->getCode());
        }
        if ($exception instanceof HttpErrorException) {
            Response::failed(error: $exception->getMessage(), code: $exception->getCode());
        }
        if ($exception instanceof \Throwable) {
            if (MainConfig::SENTRY_ENABLE) \Sentry\captureException($exception);
            Response::failed(error: $exception->getMessage());
        }
    }
}
