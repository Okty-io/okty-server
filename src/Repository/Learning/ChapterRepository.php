<?php

declare(strict_types=1);

namespace App\Repository\Learning;

use App\Entity\Learning\Chapter;
use App\ValueObject\Learning\Github\Chapter as GithubChapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class ChapterRepository implements ChapterRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Chapter::class);
    }

    /**
     * @return Chapter[]
     */
    public function findAll(string $language): array
    {
        return $this->repository->findBy(['language' => $language], ['position' => 'ASC']);
    }

    public function findById(string $id): Chapter
    {
        /** @var Chapter $chapter */
        $chapter = $this->repository->find($id);
        if (!$chapter) {
            throw new EntityNotFoundException();
        }

        return $chapter;
    }

    public function createFromValueObject(GithubChapter $chapterValue, string $language): Chapter
    {
        return new Chapter(
            Uuid::uuid4()->toString(),
            $chapterValue->getNameByLanguage($language),
            $chapterValue->getPosition(),
            $language
        );
    }

    public function save(Chapter $chapter): void
    {
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();
    }

    public function clear(): void
    {
        foreach ($this->repository->findAll() as $chapter) {
            $this->entityManager->remove($chapter);
        }

        $this->entityManager->flush();
    }
}
