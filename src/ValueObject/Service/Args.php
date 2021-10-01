<?php

declare(strict_types=1);

namespace App\ValueObject\Service;

use LogicException;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Args
{
    private Id $id;
    /** @var string */

    private $image;

    /** @var string */
    private $version;

    /** @var array */
    private $compose;

    /** @var array */
    private $ports;

    /** @var array */
    private $volumes;

    /** @var array */
    private $environments;

    /** @var array */
    private $fileArgs;

    private bool $fromBuilder;

    public function __construct(array $config)
    {
        if (empty($config['image']) || !isset($config['args'])) {
            throw new LogicException('Missing mandatory field(s)');
        }
        $this->image = $config['image'];

        $args = $config['args'];

        $this->id = new Id($args['id'] ?? $config['image']);
        $this->version = $args['version'] ?? '';

        $this->compose = [];
        foreach ($args['compose'] ?? [] as $compose) {
            $key = $compose['key'] ?? '';
            $value = $compose['value'] ?? '';

            $this->compose[] = new Option($key, $value);
        }

        $this->ports = [];
        foreach ($args['ports'] ?? [] as $port) {
            $host = $port['host'] ?? '';
            $container = $port['container'] ?? '';

            $this->ports[] = new Port((string) $host, (string) $container);
        }

        $this->volumes = [];
        foreach ($args['volumes'] ?? [] as $volume) {
            $host = $volume['host'] ?? '';
            $container = $volume['container'] ?? '';

            $this->volumes[] = new Volume($host, $container);
        }

        $this->environments = [];
        foreach ($args['environments'] ?? [] as $env) {
            $key = $env['key'] ?? '';
            $value = $env['value'] ?? '';

            $this->environments[] = new Environment($key, $value);
        }

        $this->fileArgs = [];
        foreach ($args['files'] ?? [] as $file) {
            $key = $file['key'] ?? '';
            $value = $file['value'] ?? '';

            $this->fileArgs[] = new FileArg($key, $value);
        }

        $this->fromBuilder = false;
        if (isset($config['builder']) && true === $config['builder']) {
            $this->fromBuilder = true;
        }
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getComposeOptions(): array
    {
        return $this->compose;
    }

    public function getPorts(): array
    {
        return $this->ports;
    }

    public function getVolumes(): array
    {
        return $this->volumes;
    }

    public function getEnvironments(): array
    {
        return $this->environments;
    }

    public function getFileArgs(): array
    {
        return $this->fileArgs;
    }

    public function getFileArgValue($argName): string
    {
        /** @var FileArg $file */
        foreach ($this->fileArgs as $file) {
            if ($file->getKey() == $argName) {
                return $file->getValue();
            }
        }

        return '';
    }

    public function isFromBuilder(): bool
    {
        return $this->fromBuilder;
    }
}
