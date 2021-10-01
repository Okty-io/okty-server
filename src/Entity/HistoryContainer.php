<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class HistoryContainer
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $image;

    /**
     * @ORM\Column(type="text")
     */
    private string $args;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="containers")
     * @ORM\JoinColumn(nullable=false)
     */
    private History $history;

    public function __construct(History $history, string $image, string $args)
    {
        $this->history = $history;
        $this->image = $image;
        $this->args = $args;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getArgs(): string
    {
        return $this->args;
    }

    public function getHistory(): History
    {
        return $this->history;
    }
}
