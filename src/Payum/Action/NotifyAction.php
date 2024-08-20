<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApiAwareTrait;
use Motherbrain\SyliusPaylinePlugin\Payum\Request\ResolveNotificationType;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;

final class NotifyAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        if (null === $request->getToken()) {
            $this->executeWebhook();
        } else {
            $this->gateway->execute(new Sync($request->getModel()));
        }
    }

    private function executeWebhook(): void
    {
        $this->gateway->execute(new ResolveNotificationType());
    }

    public function supports($request): bool
    {
        return $request instanceof Notify;
    }
}
