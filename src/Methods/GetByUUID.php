<?php

namespace Gravita\Http\Methods;

use Gravita\Http\User;
use Microwin7\PHPUtils\Response\JsonResponse;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;
use Microwin7\PHPUtils\Exceptions\UserNotFoundException;

#[AsArguments(whereSearch: 'GET', required: ['uuid'])]
class GetByUUID extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        JsonResponse::response(
            User::get_by_uuid($this->uuid)
                ?: throw new UserNotFoundException
        );
    }
}
