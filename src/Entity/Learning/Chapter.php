<?php

declare(strict_types=1);

namespace App\Entity\Learning;

use App\Annotation\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 *
 * @ORM\Entity()
 *
 * @Translatable()
 */
class Chapter
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"show"})
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Learning\Lesson", mappedBy="chapter", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Groups({"list", "show"})
     */
    private $lessons;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"list", "show"})
     *
     * @Translatable()
     */
    private $name;

    public function __construct(
        string $id,
        string $name,
        int $position,
        ?Collection $lessons = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->lessons = $lessons ?? new ArrayCollection();
    }

    public function getId(): string
    {
        return (string) $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getLessons(): Collection
    {
        return $this->lessons;
    }
}
