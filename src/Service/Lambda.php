<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\BadCredentialsException;
use Aws\Lambda\Exception\LambdaException;
use Aws\Lambda\LambdaClient;
use Aws\Result;
use GuzzleHttp\Psr7\Stream;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Lambda implements LambdaInterface
{
    private LambdaClient $lambdaClient;

    public function __construct(LambdaClient $lambdaClient)
    {
        $this->lambdaClient = $lambdaClient;
    }

    public function invoke($function, $resolver, $arg): string
    {
//        $key = sprintf('%s.%s.%s',
//            hash('sha256', $function),
//            hash('sha256', $resolver),
//            hash('sha256', $arg)
//        );
//
//        if ($this->cache->has($key)) {
//            return (string) $this->cache->get($key);
//        }

        try {
            /** @var Result $response */
            $response = $this->lambdaClient->invoke([
                'FunctionName' => $function,
                'Payload' => json_encode(['name' => $resolver, 'value' => $arg]),
            ]);
        } catch (LambdaException $exception) {
            if (404 == $exception->getStatusCode()) {
                throw new \RuntimeException("Function $function not found", $exception->getCode(), $exception);
            }

            if (403 == $exception->getStatusCode() || 401 == $exception->getStatusCode()) {
                throw new BadCredentialsException('AWS Lambda');
            }

            throw new \RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if ($response->get('FunctionError')) {
            return $arg;
        }

        /** @var Stream $stream */
        $stream = $response->get('Payload');

        $output = '';
        while (!$stream->eof() || strlen($output) > 8096) {
            $output .= $stream->read(255);
        }

//        $this->cache->set($key, $output);

        return trim($output, '"');
    }
}
