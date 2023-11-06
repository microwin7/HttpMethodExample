<?php

namespace Gravita\Http\Methods;

use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['refreshToken'])]
class RefreshToken extends RequiredArguments implements IActionHandler
{
    function execute()
    {
        UserSession::$DB = (new Connector)->{''};
        Response::response(
            UserSession::get_by_refresh_token($this->refreshToken)->refresh()
        );
    }
}
