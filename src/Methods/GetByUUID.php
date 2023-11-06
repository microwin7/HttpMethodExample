<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Microwin7\PHPUtils\DB\Connector;
use Gravita\Http\Exceptions\UserNotFound;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'GET', required: ['uuid'])]
class GetByUUID extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        User::$DB = (new Connector)->{''};
        Response::response(
            User::get_by_uuid($this->uuid)
                ?: throw new UserNotFound
        );
    }
}
