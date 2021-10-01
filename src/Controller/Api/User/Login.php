<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\ValueObject\Json;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Login
{
    private JWTTokenManagerInterface $tokenManager;

    public function __construct(
        JWTTokenManagerInterface $tokenManager
    ) {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function handle(Request $request): JsonResponse
    {
        $args = (new Json($request->getContent()))->getValue();

        if (!method_exists($this, $args['provider'])) {
            throw new NotFoundHttpException();
        }

        $user = $this->{$args['provider']}($args);

        return new JsonResponse([
            'token' => $this->tokenManager->create($user),
        ]);
    }
}
