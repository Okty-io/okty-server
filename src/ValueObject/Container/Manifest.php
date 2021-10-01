<?php

declare(strict_types=1);

namespace App\ValueObject\Container;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Manifest
{
    /**
     * @var string|mixed
     */
    private $image;
    /**
     * @var string|mixed
     */
    private $build;
    private $tag;

    private array $files;

    public function __construct(array $docker, array $files = [], array $config = [])
    {
        $this->image = $docker['image'] ?? '';
        $this->build = $docker['build'] ?? '';
        $this->tag = $docker['tag'] ?? '';

        $this->files = [];
        foreach ($files as $filename) {
            if (!$config[$filename]) {
                continue;
            }

            $this->files[$filename] = new ManifestSourceConfig($config[$filename]);
        }
    }

    public function hasBuild(): bool
    {
        return (bool) $this->build;
    }

    public function getBuild(): string
    {
        return $this->build;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getTag(): string
    {
        return $this->tag ?? 'latest';
    }

    public function hasFiles(): bool
    {
        return !empty($this->files);
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
