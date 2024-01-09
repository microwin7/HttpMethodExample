<?php

namespace Gravita\Http\Methods;

use Gravita\Http\UserSession;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['uuid'])]
class ExitUser extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        UserSession::exit_user($this->uuid);
    }
}
