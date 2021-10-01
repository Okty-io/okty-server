<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Service
{
    private string $id;
    private $image;
    private $build;
    private $options;
    private $ports;
    private $volumes;
    private $environments;

    public function __construct(
        string $id,
        array $image,
        array $options,
        array $ports,
        array $volumes,
        array $environments
    ) {
        $this->id = $id;

        $this->image = $image['image'] ?? '';
        $this->build = $image['build'] ?? '';

        if (empty($this->image) && empty($this->build)) {
            throw new \LogicException('At least one of these two options is required (Image/Build');
        }

        $this->options = $options;
        $this->ports = $ports;
        $this->volumes = $volumes;
        $this->environments = $environments;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @return mixed[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return mixed[]
     */
    public function getPorts(): array
    {
        return $this->ports;
    }

    /**
     * @return mixed[]
     */
    public function getVolumes(): array
    {
        return $this->volumes;
    }

    /**
     * @return mixed[]
     */
    public function getEnvironments(): array
    {
        return $this->environments;
    }
}
