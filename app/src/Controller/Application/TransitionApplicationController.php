<?php

declare(strict_types=1);

namespace App\Controller\Application;

use App\Domain\Application\ApplicationOutput;
use App\Domain\Application\Transformer\EntityToOutputTransformer;
use App\Domain\Application\TransitionApplicationPayload;
use App\Entity\Application;
use App\Supportive\OpenApi\Example;
use App\Supportive\OpenApi\JsonArrayContent;
use App\Supportive\OpenApi\UnprocessableEntityResponse;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsController]
final readonly class TransitionApplicationController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private WorkflowInterface $applicationStateMachine,
        private EntityToOutputTransformer $entityToOutputTransformer,
    ) {
    }

    #[OA\Patch(
        operationId: 'transitionApplication',
        description: 'Execute transition of the Application\'s status',
        summary: 'Update the Application status',
        requestBody: new OA\RequestBody(
            description: 'Application with updated status',
            content: new OA\JsonContent(
                ref: new Model(type: TransitionApplicationPayload::class),
                example: new Example(TransitionApplicationPayload::class),
            ),
        ),
        tags: ['Application'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'The Application with updated status',
                content: new JsonArrayContent(itemType: ApplicationOutput::class)
            ),
            new UnprocessableEntityResponse(),
        ],
    )]
    #[Route(path: '/applications/{id}/transition', name: 'app_application_transition', methods: [Request::METHOD_PATCH], format: 'json')]
    public function __invoke(
        Application $application,
        #[MapRequestPayload] TransitionApplicationPayload $payload,
    ): JsonResponse {
        if (!$this->applicationStateMachine->can($application, $payload->transition->value)) {
            throw new RuntimeException('Unable to transition application');
        }

        $this->applicationStateMachine->apply($application, $payload->transition->value);
        $this->manager->flush();

        return new JsonResponse(
            $this->entityToOutputTransformer->transform($application),
        );
    }
}
