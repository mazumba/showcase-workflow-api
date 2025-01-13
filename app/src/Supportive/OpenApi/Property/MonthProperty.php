<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Property;

use Attribute;
use OpenApi\Attributes\Property;

#[Attribute]
final class MonthProperty extends Property
{
    public function __construct(string $description = 'Must be of format "Y-m".', string $example = '2024-01', bool $nullable = false)
    {
        parent::__construct(
            description: $description, type: 'string', pattern: '^2\d{3}-(0?[1-9]|1[0-2])$', example: $example, nullable: $nullable
        );
    }
}
