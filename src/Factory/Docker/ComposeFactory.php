<?php

declare(strict_types=1);

namespace App\Factory\Docker;

use App\Factory\Docker\Resolver\EnvironmentsResolver;
use App\Factory\Docker\Resolver\ImageResolver;
use App\Factory\Docker\Resolver\OptionsResolver;
use App\Factory\Docker\Resolver\PortsResolver;
use App\Factory\Docker\Resolver\VolumesResolver;
use App\ValueObject\DockerCompose;
use App\ValueObject\Service;
use App\ValueObject\Service\Args;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class ComposeFactory
{
    private ImageResolver $imageResolver;
    private PortsResolver $portsResolver;
    private VolumesResolver $volumesResolver;
    private EnvironmentsResolver $environmentsResolver;
    private OptionsResolver $optionsResolver;

    public function __construct(
        ImageResolver $imageResolver,
        PortsResolver $portsResolver,
        VolumesResolver $volumesResolver,
        EnvironmentsResolver $environmentsResolver,
        OptionsResolver $optionsResolver
    ) {
        $this->imageResolver = $imageResolver;
        $this->portsResolver = $portsResolver;
        $this->volumesResolver = $volumesResolver;
        $this->environmentsResolver = $environmentsResolver;
        $this->optionsResolver = $optionsResolver;
    }

    public function build(array $containers): DockerCompose
    {
        $services = [];

        foreach ($containers as $args) {
            if (!$args instanceof Args) {
                throw new \LogicException('');
            }

            $id = $args->getId()->getValue();
            $image = $this->imageResolver->resolve($args);
            $options = $this->optionsResolver->resolve($args);
            $ports = $this->portsResolver->resolve($args);
            $volumes = $this->volumesResolver->resolve($args);
            $environments = $this->environmentsResolver->resolve($args);

            $services[] = new Service($id, $image, $options, $ports, $volumes, $environments);
        }

        return new DockerCompose($services);
    }
}
