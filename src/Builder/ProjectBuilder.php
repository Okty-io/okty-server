<?php declare(strict_types=1);

namespace App\Builder;

use App\Builder\Resolver\FilesResolver;
use App\Builder\ValueObject\ContainerArgs;
use App\Builder\ValueObject\Project\DockerCompose;
use App\Builder\ValueObject\Project\Project;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class ProjectBuilder
{
    private $composerBuilder;
    private $filesResolver;

    public function __construct(DockerComposerBuilder $composerBuilder, FilesResolver $filesResolver)
    {
        $this->composerBuilder = $composerBuilder;
        $this->filesResolver = $filesResolver;
    }

    public function build(array $containers): Project
    {
        $files = [];
        $dockerCompose = new DockerCompose();

        foreach ($containers as $container) {
            $containerArgs = new ContainerArgs($container);

            $this->composerBuilder->build($dockerCompose, $containerArgs);
            $files = array_merge($files, $this->filesResolver->resolve($containerArgs));
        }

        return new Project($dockerCompose, $files);
    }
}
