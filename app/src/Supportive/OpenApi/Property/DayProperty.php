<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Property;

use Attribute;
use OpenApi\Attributes\Property;

#[Attribute]
final class DayProperty extends Property
{
    public function __construct(string $description = 'Must be of format "Y-m-d".', string $example = '2024-10-22')
    {
        parent::__construct(
            description: $description, type: 'string', pattern: '^\d{4}-\d{2}-\d{2}$', example: $example
        );
    }
}
