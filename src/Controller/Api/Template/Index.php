<?php

declare(strict_types=1);

namespace App\Controller\Api\Template;

use App\Repository\TemplateRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Index
{
    private TemplateRepositoryInterface $templateRepository;
    private SerializerInterface $serializer;

    public function __construct(TemplateRepositoryInterface $templateRepository, SerializerInterface $serializer)
    {
        $this->templateRepository = $templateRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("templates", methods={"GET"})
     */
    public function handle(): JsonResponse
    {
        $templates = $this->templateRepository->findAll();

        return new JsonResponse(
            $this->serializer->serialize($templates, 'json', ['groups' => ['list']]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
