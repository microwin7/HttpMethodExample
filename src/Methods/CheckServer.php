<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['username', 'serverId'])]
class CheckServer extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        User::$DB = UserSession::$DB = (new Connector)->{''};
        $session = UserSession::get_by_server_id_and_username($this->serverId, $this->username);
        Response::response(
            $session->server_id === $this->serverId
                ? $session->user
                : throw new HttpErrorException(SERVER_ID_INCORRECT)
        );
    }
}
