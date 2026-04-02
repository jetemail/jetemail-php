<?php

declare(strict_types=1);

namespace JetEmail\Enums\Transporter;

enum Method: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}
