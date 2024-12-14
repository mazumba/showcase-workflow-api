<?php

declare(strict_types=1);

namespace App\Controller\Application;

use App\Domain\Application\ApplicationOutput;
use App\Domain\Application\ApplicationPayload;
use App\Domain\Application\Transformer\EntityToOutputTransformer;
use App\Domain\Application\Transformer\EntityToPayloadTransformer;
use App\Domain\Application\Transformer\PayloadToEntityUpdater;
use App\Entity\Application;
use App\Supportive\OpenApi\Example;
use App\Supportive\OpenApi\UnprocessableEntityResponse;
use App\Supportive\Workflow\Application\TraversedStatusListProvider;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
final readonly class UpdateApplicationController
{
    public function __construct(
        private PayloadToEntityUpdater $payloadToEntityUpdater,
        private EntityToPayloadTransformer $entityToPayloadTransformer,
        private ValidatorInterface $validator,
        private TraversedStatusListProvider $statusListProvider,

        private EntityManagerInterface $entityManager,
        private EntityToOutputTransformer $entityToOutputTransformer,
    ) {
    }

    #[OA\Patch(
        operationId: 'updateApplication',
        description: 'Update an Application',
        summary: 'Update an Application',
        requestBody: new OA\RequestBody(
            description: 'Update the Application',
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: ApplicationPayload::class),
                example: new Example(ApplicationPayload::class),
            ),
        ),
        tags: ['Application'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'The updated Application',
                content: new OA\JsonContent(
                    ref: new Model(type: ApplicationOutput::class),
                    example: new Example(ApplicationOutput::class),
                ),
            ),
            new UnprocessableEntityResponse(),
        ],
    )]
    #[Route(path: '/applications/{id}', name: 'app_application_update', methods: [Request::METHOD_PATCH], format: 'json')]
    public function __invoke(
        Application $application,
        #[MapRequestPayload] ApplicationPayload $payload,
    ): JsonResponse {
        $this->payloadToEntityUpdater->update($payload, $application);

        $completePayload = $this->entityToPayloadTransformer->transform($application);
        $violations = $this->validator->validate(
            value: $completePayload,
            groups: $this->statusListProvider->getList($application),
        );

        if (\count($violations) > 0) {
            throw new ValidationFailedException($completePayload, $violations);
        }

        $this->entityManager->flush();

        return new JsonResponse(
            $this->entityToOutputTransformer->transform($application),
        );
    }
}
