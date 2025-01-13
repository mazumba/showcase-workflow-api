<?php

declare(strict_types=1);

namespace App\Controller\Application;

use App\Domain\Application\ApplicationInput;
use App\Domain\Application\ApplicationOutput;
use App\Domain\Application\ApplicationPayload;
use App\Domain\Application\Transformer\EntityToOutputTransformer;
use App\Domain\Application\Transformer\PayloadToEntityTransformer;
use App\Entity\Application;
use App\Supportive\OpenApi\Example;
use App\Supportive\OpenApi\UnprocessableEntityResponse;
use App\Supportive\Validator\Application\ApplicationGroupsProvider;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
final readonly class UpdateApplicationController
{
    public function __construct(
        private PayloadToEntityTransformer $payloadToEntityTransformer,
        private ValidatorInterface $validator,
        private ApplicationGroupsProvider $statusListProvider,

        private EntityManagerInterface $entityManager,
        private EntityToOutputTransformer $entityToOutputTransformer,
    ) {
    }

    #[OA\Put(
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
    #[Route(path: '/applications/{id}', name: 'app_application_update', methods: [Request::METHOD_PUT], format: 'json')]
    public function __invoke(
        Application $application,
        #[MapRequestPayload] ApplicationPayload $payload,
    ): JsonResponse {
        $input = new ApplicationInput($payload, $application);

        $violations = $this->validator->validate(
            value: $input,
            groups: $this->statusListProvider->getGroups($application),
        );

        if (\count($violations) > 0) {
            throw new UnprocessableEntityHttpException((new ValidationFailedException($payload, $violations))->getMessage());
        }

        $this->payloadToEntityTransformer->transform($payload, $application);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->entityToOutputTransformer->transform($application),
        );
    }
}
