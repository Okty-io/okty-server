<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Project
{
    private DockerCompose $dockerCompose;
    private array $files;

    public function __construct(DockerCompose $dockerCompose, array $files)
    {
        $this->dockerCompose = $dockerCompose;

        $this->files = [];
        foreach ($files as $file) {
            if (!$file instanceof File) {
                throw new \LogicException(sprintf('Only File type can be added to a project, %s given', gettype($file)));
            }

            $this->files[] = $file;
        }
    }

    public function getDockerCompose(): DockerCompose
    {
        return $this->dockerCompose;
    }

    /**
     * @return mixed[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
