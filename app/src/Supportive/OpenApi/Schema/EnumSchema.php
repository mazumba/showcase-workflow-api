<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Schema;

use Attribute;
use OpenApi\Attributes\Schema;
use UnitEnum;

#[Attribute]
final class EnumSchema extends Schema
{
    public function __construct(string $type, UnitEnum $example)
    {
        parent::__construct(type: $type, enum: $example::cases(), example: $example);
    }
}
