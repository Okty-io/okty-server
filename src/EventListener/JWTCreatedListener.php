<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTCreatedListener implements EventSubscriberInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        if (empty($payload['username'])) {
            return;
        }

        $user = $this->userRepository->findById($payload['username']);

        $payload['login'] = $user->getLogin();

        $event->setData($payload);
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return ['lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated'];
    }
}
