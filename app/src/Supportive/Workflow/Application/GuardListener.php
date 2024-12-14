<?php

declare(strict_types=1);

namespace App\Supportive\Workflow\Application;

use App\Domain\Application\Enum\ApplicationTransition;
use App\Domain\Application\Transformer\EntityToPayloadTransformer;
use App\Entity\Application;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Attribute\AsGuardListener;
use Symfony\Component\Workflow\Event\GuardEvent;

#[AsGuardListener(workflow: 'application', transition: ApplicationTransition::FIRST_TO_SECOND->value)]
#[AsGuardListener(workflow: 'application', transition: ApplicationTransition::FINISH->value)]
final readonly class GuardListener
{
    public function __construct(
        private EntityToPayloadTransformer $transformer,
        private ValidatorInterface $validator,
        private TraversedStatusListProvider $statusListProvider,
    ) {
    }

    public function __invoke(GuardEvent $event): void
    {
        /** @var Application $application */
        $application = $event->getSubject();

        $applicationPayload = $this->transformer->transform($application);

        $violations = $this->validator->validate(
            value: $applicationPayload,
            groups: $this->statusListProvider->getList($application),
        );

        if (\count($violations) > 0) {
            $event->setBlocked(true);
        }
    }
}