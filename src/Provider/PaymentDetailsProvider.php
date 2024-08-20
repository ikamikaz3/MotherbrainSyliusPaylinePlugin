<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Provider;

use Alcohol\ISO4217;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class PaymentDetailsProvider implements DetailsProviderInterface
{
    public function get(PaymentInterface $payment): array
    {
        $iso4217 = new ISO4217();

        $currencyCode = $payment->getCurrencyCode();
        Assert::notNull($currencyCode);

        $currency = $iso4217->getByAlpha3($currencyCode);

        $paymentDetails = [];

        $paymentDetails['amount'] = (string)$payment->getAmount();
        $paymentDetails['currency'] = $currency['numeric'];
        $paymentDetails['action'] = PaylineApi::ACTION_AUTH_CAPTURE;
        $paymentDetails['mode'] = PaylineApi::PAYMENT_MODE_FULL;

        return $paymentDetails;
    }
}
