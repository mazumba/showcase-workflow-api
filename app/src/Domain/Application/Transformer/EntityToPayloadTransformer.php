<?php

declare(strict_types=1);

namespace App\Domain\Application\Transformer;

use App\Domain\Application\ApplicationPayload;
use App\Entity\Application;

final class EntityToPayloadTransformer
{
    public function transform(Application $application): ApplicationPayload
    {
        $payload = new ApplicationPayload();
        $payload->name = $application->getName();
        $payload->estimatedExpenses = $application->getEstimatedExpenses();
        $payload->iban = $application->getIban();

        return $payload;
    }
}
