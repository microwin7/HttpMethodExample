<?php

namespace Gravita\Http\Methods;

use Gravita\Http\Utils;
use Microwin7\PHPUtils\Configs\MainConfig;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Gravita\Http\Exceptions\HttpErrorException;
use Microwin7\PHPUtils\Request\RequiredArguments;
use Microwin7\PHPUtils\Exceptions\NoSuchRequestMethodException;
use Microwin7\PHPUtils\Security\BearerToken;

#[AsArguments(whereSearch: 'GET', required: ['method'])]
class MethodInit extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        BearerToken::validateBearer() ?: throw new HttpErrorException(BEARER_TOKEN_INCORRECT);

        match (MethodTypeEnum::tryFrom($this->method)) {
            MethodTypeEnum::AUTHORIZE => (new Authorize)->execute(),
            MethodTypeEnum::REFRESH_ACCESS_TOKEN => (new RefreshToken)->execute(),
            MethodTypeEnum::GET_USER_SESSION_BY_OAUTH_ACCESS_TOKEN => (new GetByToken)->execute(),
            MethodTypeEnum::GET_USER_BY_USERNAME => (new GetByUsername)->execute(),
            MethodTypeEnum::GET_USER_BY_UUID => (new GetByUUID)->execute(),
            MethodTypeEnum::CHECK_SERVER => (new CheckServer)->execute(),
            MethodTypeEnum::JOIN_SERVER => (new JoinServer)->execute(),
            MethodTypeEnum::DELETE_SESSION => (new DeleteSession)->execute(),
            MethodTypeEnum::EXIT_USER => (new ExitUser)->execute(),
            default => throw new NoSuchRequestMethodException
        };
    }
}
