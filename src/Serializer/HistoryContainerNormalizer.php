<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\HistoryContainer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HistoryContainerNormalizer implements NormalizerInterface
{
    /**
     * @return array<string, string>
     */
    public function normalize($container, $format = null, array $context = []): array
    {
        /* @var HistoryContainer $container */
        return [
            'image' => $container->getImage(),
            'args' => $container->getArgs(),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof HistoryContainer;
    }
}
