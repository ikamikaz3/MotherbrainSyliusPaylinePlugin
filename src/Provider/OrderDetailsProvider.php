<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Provider;

use Alcohol\ISO4217;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class OrderDetailsProvider implements DetailsProviderInterface
{
    public function get(PaymentInterface $payment): array
    {
        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $currencyCode = $payment->getCurrencyCode();
        Assert::notNull($currencyCode);
        $currency = (new ISO4217())->getByAlpha3($currencyCode);

        $orderDetails = [];
        $orderDetails['amount'] = (string)$payment->getAmount();
        $orderDetails['currency'] = $currency['numeric'];
        $orderDetails['ref'] = $order->getNumber();
        Assert::notNull($checkoutCompletedAt = $order->getCheckoutCompletedAt());
        $orderDetails['date'] = $checkoutCompletedAt->format('d/m/Y H:i');

        return $orderDetails;
    }
}
