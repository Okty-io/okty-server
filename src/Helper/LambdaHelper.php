<?php declare(strict_types=1);

namespace App\Helper;

use Aws\Lambda\Exception\LambdaException;
use Aws\Lambda\LambdaClient;
use GuzzleHttp\Psr7\Stream;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class LambdaHelper
{
    private $lambdaClient;

    public function __construct(LambdaClient $lambdaClient)
    {
        $this->lambdaClient = $lambdaClient;
    }

    public function invoke($function, $resolver, $arg)
    {
        try {
            /** @var \Aws\Result $response */
            $response = $this->lambdaClient->invoke([
                'FunctionName' => $function,
                'Payload' => json_encode(['name' => $resolver, 'value' => $arg])
            ]);
        } catch (LambdaException $exception) {
            if ($exception->getStatusCode() == 404) {
                throw new \RuntimeException("Function $function not found");
            }

            throw new \RuntimeException($exception->getMessage());
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

        return $output;
    }
}
