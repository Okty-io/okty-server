<?php

namespace App\Controller;

use App\Exception\BadCredentialsException;
use App\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ErrorController;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Exception
{
    private ErrorController $errorController;

    public function __construct(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }

    public function __invoke(\Throwable $exception): Response
    {
        if ($exception instanceof FileNotFoundException) {
            return new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
        }

        if ($exception instanceof BadCredentialsException) {
            return new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
        }

        if ($exception instanceof \LogicException) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->errorController->__invoke($exception);
    }
}
