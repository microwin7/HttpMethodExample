<?php

namespace Gravita\Http\Methods;

use Gravita\Http\UserSession;
use Microwin7\PHPUtils\DB\Connector;
use Microwin7\PHPUtils\Attributes\AsArguments;
use Microwin7\PHPUtils\Request\RequiredArguments;

#[AsArguments(whereSearch: 'JSON', required: ['id'])]
class DeleteSession extends RequiredArguments implements IActionHandler
{
    public function execute()
    {
        UserSession::$DB = (new Connector)->{''};
        UserSession::delete_session($this->id);
    }
}
