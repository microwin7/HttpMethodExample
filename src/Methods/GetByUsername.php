<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Microwin7\PHPUtils\DB\Connector;
use Gravita\Http\Exceptions\UserNotFound;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'GET', required: ['username'])]
class GetByUsername extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        User::$DB = (new Connector)->{''};
        Response::response(
            User::get_by_username($this->username)
                ?: throw new UserNotFound
        );
    }
}
