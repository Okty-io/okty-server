<?php

declare(strict_types=1);

namespace App\Controller\Api\Learning;

use App\ValueObject\Json;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Deploy
{
    private $serializer;
    private $logger;

    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @Route("learning/deploy", methods={"POST"})
     */
    public function handle(Request $request): JsonResponse
    {
//        $data = new Json($request->getContent());
        $this->logger->error($request->getContent());

        return new JsonResponse(
            $this->serializer->serialize($request->getContent(), 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
