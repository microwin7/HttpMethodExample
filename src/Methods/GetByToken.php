<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Response\Response;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['accessToken'])]
class GetByToken extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        User::$DB = UserSession::$DB = (new Connector)->{''};
        if (is_null($session = UserSession::get_by_access_token_with_user($this->accessToken)))
            throw new HttpErrorException(AUTH_INVALID_TOKEN);

        Response::response(
            $session->expire_in >= time()
                ? $session
                : throw new HttpErrorException(AUTH_TOKEN_EXPIRED)
        );
    }
}
