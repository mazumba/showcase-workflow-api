<?php

declare(strict_types=1);

namespace App\Domain\Application\Enum;

enum ApplicationStatus: string
{
    case FIRST = 'first';
    case SECOND = 'second';
    case DONE = 'done';
}
