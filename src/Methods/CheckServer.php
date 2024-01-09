<?php

namespace Gravita\Http\Methods;

use Gravita\Http\UserSession;
use Microwin7\PHPUtils\Response\JsonResponse;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['username', 'serverId'])]
class CheckServer extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        $session = UserSession::get_by_server_id_and_username($this->serverId, $this->username);
        JsonResponse::response(
            $session->server_id === $this->serverId
                ? $session->user
                : throw new HttpErrorException(SERVER_ID_INCORRECT)
        );
    }
}
