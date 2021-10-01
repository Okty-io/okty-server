<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Container
{
    /**
     * @Groups({"list"})
     */
    private string $id;

    /**
     * @Groups({"list"})
     */
    private string $name;

    /**
     * @Groups({"list"})
     */
    private string $logo;

    private array $config;

    public function __construct(string $id, string $name, string $logo, array $config = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->logo = $logo;
        $this->config = $config;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
