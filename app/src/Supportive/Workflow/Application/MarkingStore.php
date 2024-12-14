<?php

declare(strict_types=1);

namespace App\Supportive\Workflow\Application;

use App\Domain\Application\Enum\ApplicationStatus;
use App\Entity\Application;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

final class MarkingStore implements MarkingStoreInterface
{
    /**
     * @param Application&object $subject
     */
    public function getMarking(object $subject): Marking
    {
        return new Marking([$subject->getStatus()->value => 1]);
    }

    /**
     * @param Application&object $subject
     * @param array<array-key, string> $context
     */
    public function setMarking(object $subject, Marking $marking, array $context = []): void
    {
        /** @var string $markingKey */
        $markingKey = key($marking->getPlaces());
        $subject->setStatus(ApplicationStatus::from($markingKey));
    }
}
