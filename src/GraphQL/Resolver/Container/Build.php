<?php declare(strict_types=1);

namespace App\GraphQL\Resolver\Container;

use App\Builder\ContainerBuilder;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Build implements ResolverInterface
{
    private $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    public function __invoke(string $args): string
    {
        /** @var array|false $containers */
        $containers = json_decode($args, true);
        if ($containers === false) {
            die('json format error');
        }

        foreach ($containers as $container) {
            if (!isset($container['name']) || !isset($container['args'])) {
                continue;
            }

            $files = $this->containerBuilder->build($container['name'], $container['args']);

            print_r($files);
        }

        return 'yes';
    }
}