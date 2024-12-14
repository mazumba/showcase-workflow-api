<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Property;

use Attribute;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use UnitEnum;

#[Attribute]
final class EnumArrayProperty extends Property
{
    /**
     * @param UnitEnum[] $exclude
     */
    public function __construct(UnitEnum $example, ?string $description = null, ?string $type = 'string', array $exclude = [])
    {
        parent::__construct(
            description: $description,
            type: 'array',
            items: new Items(
                type: $type,
                enum: array_udiff($example::cases(), $exclude, static fn (UnitEnum $a, UnitEnum $b) => $a->name <=> $b->name),
                example: $example,
            ),
            example: [$example],
        );
    }
}
