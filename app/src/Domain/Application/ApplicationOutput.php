<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Enum\ApplicationStatus;
use App\Supportive\OpenApi\Schema\ObjectSchema;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

#[ObjectSchema(self::class)]
final readonly class ApplicationOutput
{
    public function __construct(
        #[OA\Property(type: 'string', example: '6748c71c-475b-4fc6-a85b-33f8918d77fc')]
        public Uuid $id,
        #[OA\Property(example: ApplicationStatus::FIRST)]
        public ApplicationStatus $status,
        #[OA\Property(example: 'Test Application')]
        public ?string $name,
        #[OA\Property(example: 20000)]
        public ?int $estimatedExpenses,
        #[OA\Property(example: 'DE1212341234123400')]
        public ?string $iban,
    ) {
    }
}
