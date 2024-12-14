<?php

declare(strict_types=1);

namespace App\Domain\Application\Transformer;

use App\Domain\Application\ApplicationOutput;
use App\Entity\Application;

final class EntityToOutputTransformer
{
    public function transform(Application $application): ApplicationOutput
    {
        return new ApplicationOutput(
            id: $application->getId(),
            status: $application->getStatus(),
            name: $application->getName(),
            estimatedExpenses: $application->getEstimatedExpenses(),
            iban: $application->getIban(),
        );
    }
}
