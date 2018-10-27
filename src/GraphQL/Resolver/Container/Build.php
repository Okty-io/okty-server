<?php declare(strict_types=1);

namespace App\GraphQL\Resolver\Container;

use App\Builder\ContainerBuilder;
use App\Helper\ZipHelper;
use App\Provider\Cloud;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Build implements ResolverInterface
{
    private $containerBuilder;
    private $zipHelper;
    private $cloud;

    public function __construct(ContainerBuilder $containerBuilder, ZipHelper $zipHelper, Cloud $cloud)
    {
        $this->containerBuilder = $containerBuilder;
        $this->zipHelper = $zipHelper;
        $this->cloud = $cloud;
    }

    public function __invoke(string $args): string
    {
        /** @var array|false $containers */
        $containers = json_decode($args, true);
        if (!$containers) {
            throw new UserError('JSON Syntax Error');
        }

        try {
            $files = $this->containerBuilder->buildAll($containers);
            $zip = $this->zipHelper->zip($files);
            $url = $this->cloud->upload($zip);
        } catch (\RuntimeException $exception) {
            throw new UserError($exception->getMessage());
        }

        return $url;
    }
}
