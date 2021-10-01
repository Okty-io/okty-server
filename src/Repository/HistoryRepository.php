<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class HistoryRepository implements HistoryRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(History::class);
    }

    /**
     * @return History[]
     */
    public function findAllByUserId(string $userId): array
    {
        return $this->repository->findBy(['user' => $userId]);
    }

    public function save(History $history): void
    {
        $this->entityManager->persist($history);
        foreach ($history->getContainers() as $container) {
            $this->entityManager->persist($container);
        }

        $this->entityManager->flush();
    }
}
