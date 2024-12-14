<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi;

use Attribute;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Schema;

#[Attribute]
final class JsonArrayContent extends MediaType
{
    /**
     * @param class-string $itemType
     */
    public function __construct(string $itemType)
    {
        $schema = new Schema(
            type: 'array',
            items: new Items(ref: new Model(type: $itemType)),
            example: [new Example($itemType)]
        );
        parent::__construct(mediaType: 'application/json', schema: $schema, example: [new Example($itemType)]);
    }
}
