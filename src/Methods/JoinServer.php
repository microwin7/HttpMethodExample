<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['accessToken', 'serverId', ['username', 'uuid']])]
class JoinServer extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        User::$DB = UserSession::$DB = (new Connector)->{''};
        $session = UserSession::get_by_access_token_with_user($this->accessToken);
        null !== $session ?: throw new HttpErrorException(SESSION_NOT_FOUND);

        if ($session->access_token !== $this->accessToken) throw new HttpErrorException(ACCESS_TOKEN_INCORRECT);
        if ($session->expire_in < time()) throw new HttpErrorException(AUTH_TOKEN_EXPIRED);
        if ($this->username && $session->user->username !== $this->username) {
            throw new HttpErrorException(USERNAME_INCORRECT);
        } else if ($this->uuid && $session->user->uuid !== $this->uuid) {
            throw new HttpErrorException(UUID_INCORRECT);
        }
        $session->update_server_id($this->serverId);
    }
}
