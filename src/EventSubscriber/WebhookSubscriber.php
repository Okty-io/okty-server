<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\Api\Learning\Deploy;
use App\ValueObject\Json;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class WebhookSubscriber implements EventSubscriberInterface
{
    private string $webhookSecret;
    private string $webhookBranch;

    public function __construct(string $webhookSecret, string $webhookBranch)
    {
        $this->webhookSecret = $webhookSecret;
        $this->webhookBranch = $webhookBranch;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        if (!$controller[0] instanceof Deploy) {
            return;
        }

        if (!$this->checkBranch($event->getRequest())) {
            throw new AccessDeniedHttpException('This branch is not allowed in the environment');
        }

        if (!$this->checkSignature($event->getRequest())) {
            throw new AccessDeniedHttpException("The signature doesn't match");
        }
    }

    private function checkSignature(Request $request): bool
    {
        $payload = $request->getContent();
        $signature = $request->server->get('HTTP_X_HUB_SIGNATURE');

        list($algo, $hash) = explode('=', $signature, 2);

        $payloadHash = hash_hmac($algo, (string) $payload, $this->webhookSecret);

        return true === hash_equals($hash, $payloadHash);
    }

    private function checkBranch(Request $request): bool
    {
        $payload = (new Json($request->getContent()))->getValue();

        return $payload['ref'] === 'refs/heads/'.$this->webhookBranch;
    }
}
