<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi;

use Attribute;
use OpenApi\Attributes\Response;
use Symfony\Component\HttpFoundation;

#[Attribute(Attribute::TARGET_METHOD)]
final class UnprocessableEntityResponse extends Response
{
    public function __construct(
        string $description = 'Invalid data.',
    ) {
        parent::__construct(
            response: HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY,
            description: $description
        );
    }
}
