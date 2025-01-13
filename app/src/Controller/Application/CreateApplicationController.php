<?php

declare(strict_types=1);

namespace App\Controller\Application;

use App\Domain\Application\ApplicationOutput;
use App\Domain\Application\ApplicationPayload;
use App\Domain\Application\Transformer\EntityToOutputTransformer;
use App\Domain\Application\Transformer\PayloadToEntityTransformer;
use App\Entity\Application;
use App\Supportive\OpenApi\Example;
use App\Supportive\OpenApi\UnprocessableEntityResponse;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class CreateApplicationController
{
    public function __construct(
        private PayloadToEntityTransformer $payloadToEntityTransformer,
        private EntityManagerInterface $entityManager,
        private EntityToOutputTransformer $entityToOutputTransformer,
    ) {
    }

    #[OA\Post(
        operationId: 'createApplication',
        description: 'Create an Application',
        summary: 'Create an Application',
        requestBody: new OA\RequestBody(
            description: 'Create the Application',
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
                description: 'The created Application',
                content: new OA\JsonContent(
                    ref: new Model(type: ApplicationOutput::class),
                    example: new Example(ApplicationOutput::class),
                ),
            ),
            new UnprocessableEntityResponse(),
        ],
    )]
    #[Route(path: '/applications', name: 'app_application_create', methods: [Request::METHOD_POST], format: 'json')]
    public function __invoke(
        #[MapRequestPayload] ApplicationPayload $payload,
    ): JsonResponse {
        $application = $this->payloadToEntityTransformer->transform($payload, new Application());

        $this->entityManager->persist($application);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->entityToOutputTransformer->transform($application),
        );
    }
}
