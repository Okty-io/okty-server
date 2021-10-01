<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\History;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HistoryNormalizer implements NormalizerInterface
{
    private HistoryContainerNormalizer $historyContainerNormalizer;

    public function __construct(HistoryContainerNormalizer $historyContainerNormalizer)
    {
        $this->historyContainerNormalizer = $historyContainerNormalizer;
    }


    public function normalize($history, $format = null, array $context = []): array
    {
        $containers = [];
        foreach ($history->getContainers() as $container) {
            $containers[] = $this->historyContainerNormalizer->normalize($container, $format, $context);
        }

        return [
            'id' => $history->getId(),
            'user' => $history->getUser()->getId(),
            'containers' => $containers,
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof History;
    }
}
