<?php

declare(strict_types=1);

namespace App\Domain\Application\Transformer;

use App\Domain\Application\ApplicationPayload;
use App\Entity\Application;

final class PayloadToEntityUpdater
{
    public function update(ApplicationPayload $payload, Application $application): Application
    {
        foreach (get_object_vars($payload) as $property => $value) {
            $setterMethod = 'set' . ucfirst($property);
            if (method_exists($application, $setterMethod)) {
                $application->$setterMethod($value);
            }
        }

        return $application;
    }
}
