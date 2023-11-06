<?php

namespace Gravita\Http\Exceptions;

class UserNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("User Not Found", 404);
    }
}
