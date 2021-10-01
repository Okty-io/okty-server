<?php

namespace App\Repository;

use App\Entity\History;
use App\Entity\HistoryContainer;
use App\ValueObject\Service\Args;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HistoryContainerRepository implements HistoryContainerRepositoryInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createFromArgs(History $history, Args $args): HistoryContainer
    {
        return new HistoryContainer(
            $history,
            $args->getImage(),
            $this->serializer->serialize($args, 'json')
        );
    }
}
