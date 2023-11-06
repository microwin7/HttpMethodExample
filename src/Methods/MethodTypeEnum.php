<?php

declare(strict_types=1);

namespace Gravita\Http\Methods;

enum MethodTypeEnum: string
{
    case AUTHORIZE                              = 'authorize';
    case REFRESH_ACCESS_TOKEN                   = 'refreshAccessToken';
    case GET_USER_SESSION_BY_OAUTH_ACCESS_TOKEN = 'getUserSessionByOAuthAccessToken';
    case GET_USER_BY_USERNAME                   = 'getUserByUsername';
    case GET_USER_BY_UUID                       = 'getUserByUUID';
    case CHECK_SERVER                           = 'checkServer';
    case JOIN_SERVER                            = 'joinServer';
    case DELETE_SESSION                         = 'deleteSession';
    case EXIT_USER                              = 'exitUser';
}
