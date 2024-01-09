<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Gravita\Http\UserSession;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;
use Microwin7\PHPUtils\Response\JsonResponse;

#[AsArguments(whereSearch: 'JSON', required: ['login', 'password'], optional: ['totpCode'])]
class Authorize extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        if (is_null($user = User::get_by_username($this->login)))
            throw new HttpErrorException(AUTH_USER_NOT_FOUND);
        $user->verify_password($this->password);

        if (false /* you can implement check: user enabled 2FA */) {
            if ($this->totpCode) {
                if (true /* you can implement check: totp code is "wrong"*/) {
                    throw new HttpErrorException(AUTH_WRONG_TOTP);
                }
            } else {
                throw new HttpErrorException(AUTH_REQUIRE_2FA);
            }
        }
        JsonResponse::response(UserSession::create_for_user($user));
    }
}
