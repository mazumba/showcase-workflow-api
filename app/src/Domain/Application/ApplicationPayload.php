<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Enum\ApplicationStatus;
use App\Supportive\OpenApi\Schema\ObjectSchema;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[ObjectSchema(self::class)]
final class ApplicationPayload
{
    #[OA\Property(example: 'Company name')]
    #[Assert\NotNull(groups: [ApplicationStatus::FIRST->value])]
    #[Assert\Length(min: 1)]
    public ?string $name;

    #[OA\Property(description: 'The estimated expected expenses (Eurocent)', example: 20000)]
    #[Assert\NotNull(groups: [ApplicationStatus::FIRST->value])]
    #[Assert\Positive]
    public ?int $estimatedExpenses;

    #[OA\Property(example: 'DE89370400440532013000')]
    #[Assert\NotNull(groups: [ApplicationStatus::SECOND->value])]
    #[Assert\Length(min: 1)]
    #[Assert\Iban]
    public ?string $iban;
}
