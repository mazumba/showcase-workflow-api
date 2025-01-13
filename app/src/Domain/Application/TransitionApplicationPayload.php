<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Enum\ApplicationTransition;
use App\Supportive\OpenApi\Schema\ObjectSchema;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[ObjectSchema(self::class)]
final class TransitionApplicationPayload
{
    #[OA\Property(example: ApplicationTransition::FIRST_TO_SECOND)]
    #[Assert\NotNull]
    public ApplicationTransition $transition;
}
