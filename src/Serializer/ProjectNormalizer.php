<?php

declare(strict_types=1);

namespace App\Serializer;

use App\ValueObject\File;
use App\ValueObject\Project;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class ProjectNormalizer implements NormalizerInterface
{
    private $composeNormalizer;

    public function __construct(ComposeNormalizer $composeNormalizer)
    {
        $this->composeNormalizer = $composeNormalizer;
    }

    public function normalize($project, $format = null, array $context = [])
    {
        /** @var Project $project */

        $files = $project->getFiles();
        $compose = $project->getDockerCompose();

        $header = "# Generated by Okty.io";
        $output = YAML::dump($this->composeNormalizer->normalize($compose), 5);

        $composeContent = sprintf("%s%s%s", $header, PHP_EOL, $output);

        return array_merge([new File('docker-compose.yml', $composeContent)], $files);
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Project;
    }
}
