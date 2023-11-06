<?php

namespace Gravita\Http\Methods;

use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['uuid'])]
class ExitUser extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        UserSession::$DB = (new Connector)->{''};
        UserSession::exit_user($this->uuid);
    }
}
