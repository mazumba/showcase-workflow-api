<?php

declare(strict_types=1);

namespace App\Controller\Application;

use App\Domain\Application\ApplicationOutput;
use App\Domain\Application\Transformer\EntityToOutputTransformer;
use App\Entity\Application;
use App\Supportive\OpenApi\JsonArrayContent;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class ListApplicationController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityToOutputTransformer $entityToOutputTransformer,
    ) {
    }

    #[OA\Get(
        operationId: 'listApplications',
        description: 'Get a list of Applications',
        summary: 'List of Applications',
        tags: ['Application'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'List of Applications',
                content: new JsonArrayContent(itemType: ApplicationOutput::class),
            ),
        ],
    )]
    #[Route(path: '/applications', name: 'app_application_list', methods: [Request::METHOD_GET], format: 'json')]
    public function __invoke(): JsonResponse
    {
        $applications = $this->entityManager->getRepository(Application::class)->findAll();

        return new JsonResponse(
            array_map(
                fn (Application $application) => $this->entityToOutputTransformer->transform($application),
                $applications
            )
        );
    }
}
