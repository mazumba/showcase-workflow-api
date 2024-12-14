<?php

declare(strict_types=1);

namespace App\Domain\Application\Enum;

enum ApplicationTransition: string
{
    case FIRST_TO_SECOND = 'first_to_second';
    case SECOND_TO_FIRST = 'second_to_first';
    case FINISH = 'finish';
}
