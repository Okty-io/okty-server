<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Factory\Docker\ComposeFactory;
use App\ValueObject\Json;
use App\ValueObject\Service\Args;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Preview
{
    private ComposeFactory $builder;
    private NormalizerInterface $normalizer;

    public function __construct(ComposeFactory $builder, NormalizerInterface $normalizer)
    {
        $this->builder = $builder;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("preview", methods={"POST"})
     */
    public function single(Request $request): JsonResponse
    {
        $args = new Json($request->getContent());
        $containerArgs = new Args($args->getValue());

        $compose = $this->builder->build([$containerArgs]);

        return new JsonResponse(['content' => $this->normalizer->normalize($compose)]);
    }

    /**
     * @Route("preview/full", methods={"POST"})
     */
    public function full(Request $request): JsonResponse
    {
        $args = new Json($request->getContent());

        $containers = [];
        foreach ($args->getValue() as $config) {
            $containers[] = new Args($config);
        }

        $compose = $this->builder->build($containers);

        return new JsonResponse(['content' => $this->normalizer->normalize($compose)]);
    }
}
