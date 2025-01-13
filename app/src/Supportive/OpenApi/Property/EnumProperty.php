<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Property;

use Attribute;
use OpenApi\Attributes\Property;
use UnitEnum;

#[Attribute]
final class EnumProperty extends Property
{
    public function __construct(
        UnitEnum $example,
        ?string $description = null,
        ?string $type = 'string',
        bool $nullable = false,
    ) {
        parent::__construct(
            description: $description,
            type: $type,
            enum: $example::cases(),
            example: $example,
            nullable: $nullable
        );
    }
}
