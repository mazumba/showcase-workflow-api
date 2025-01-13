<?php

declare(strict_types=1);

namespace App\Supportive\Validator\Application;

use App\Entity\Application;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class ApplicationGroupsProvider
{
    public function __construct(
        private WorkflowInterface $applicationStateMachine,
    ) {
    }

    /**
     * @return string[]
     */
    public function getGroups(Application $application): array
    {
        $statusList = ['Default'];
        foreach ($this->applicationStateMachine->getDefinition()->getPlaces() as $status) {
            $statusList[] = $status;
            if ($status === $application->getStatus()->value) {
                break;
            }
        }

        return $statusList;
    }
}
