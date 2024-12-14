<?php

declare(strict_types=1);

namespace App\Supportive\Workflow\Application;

use App\Entity\Application;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class TraversedStatusListProvider
{
    public function __construct(
        private WorkflowInterface $applicationStateMachine,
    ) {
    }

    /**
     * @return string[]
     */
    public function getList(Application $application): array
    {
        $statusList = [];
        foreach ($this->applicationStateMachine->getDefinition()->getPlaces() as $status) {
            $statusList[] = $status;
            if ($status === $application->getStatus()->value) {
                break;
            }
        }

        return $statusList;
    }
}
