<?php

declare(strict_types=1);

namespace App\Factory\Docker\Resolver;

use App\ValueObject\Service\Args;
use App\ValueObject\Service\Option;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class OptionsResolver
{
    /**
     * @return array<string, mixed>
     */
    public function resolve(Args $args): array
    {
        $output = [];

        /** @var Option $option */
        foreach ($args->getComposeOptions() as $option) {
            $value = $option->getValue();

            if ('command' === $option->getKey()) {
                $value = $this->formatCommandValue($value);
                $value = 1 === count($value) ? reset($value) : $value;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            $output[$option->getKey()] = $value;
        }

        return $output;
    }

    /**
     * @return string[]
     */
    private function formatCommandValue(string $value): array
    {
        if (false === strpos($value, '&&')) {
            return [$value];
        }

        return ['/bin/sh', '-c', $value];
    }
}
