<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Motherbrain\SyliusPaylinePlugin\Payum\Request\ResolveNotificationType;
use Motherbrain\SyliusPaylinePlugin\Payum\Request\WebhookEvent\WebTransactionEvent;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Webmozart\Assert\Assert;

final class ResolveNotificationTypeAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $httpRequest = new GetHttpRequest();
        $this->gateway->execute($httpRequest);

        $notificationType = $httpRequest->query['notificationType'] ?? null;
        Assert::notNull($notificationType);

        if (PaylineApi::NOTIFICATION_TYPE_WEBTRS === $notificationType) {
            $token = $httpRequest->query['token'] ?? null;
            Assert::notNull($token);

            $this->gateway->execute(new WebTransactionEvent($token));
        }
    }

    public function supports($request): bool
    {
        return $request instanceof ResolveNotificationType;
    }
}
