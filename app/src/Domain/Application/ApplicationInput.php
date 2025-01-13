<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Enum\ApplicationStatus;
use App\Entity\Application;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ApplicationInput
{
    public function __construct(
        #[Assert\Valid]
        public ApplicationPayload $payload,
        public Application $application,
    ) {
    }

    #[Assert\Choice(choices: [ApplicationStatus::FIRST, ApplicationStatus::SECOND], message: 'The Application is not editable.')]
    public function isEditable(): ApplicationStatus
    {
        return $this->application->getStatus();
    }
}
