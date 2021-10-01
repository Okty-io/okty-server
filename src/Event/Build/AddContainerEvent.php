<?php

declare(strict_types=1);

namespace App\Event\Build;

use App\ValueObject\DockerCompose;
use App\ValueObject\Service;
use App\ValueObject\Service\Args;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class AddContainerEvent extends Event
{
    private DockerCompose $project;
    private Args $args;
    private Service $service;

    public function __construct(DockerCompose $project, Args $args, Service $service)
    {
        $this->project = $project;
        $this->args = $args;
        $this->service = $service;
    }

    public function getProject(): DockerCompose
    {
        return $this->project;
    }

    public function getArgs(): Args
    {
        return $this->args;
    }

    public function getService(): Service
    {
        return $this->service;
    }
}
