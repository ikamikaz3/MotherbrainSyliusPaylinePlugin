<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

final class DetailsProvider implements DetailsProviderInterface
{
    public function __construct(
        private PaymentDetailsProvider $paymentDetailsProvider,
        private OrderDetailsProvider $orderDetailsProvider,
        private CustomerDetailsProvider $customerDetailsProvider
    ) {
    }

    public function get(PaymentInterface $payment): array
    {
        $details = [];

        $details['payment'] = $this->paymentDetailsProvider->get($payment);
        $details['order'] = $this->orderDetailsProvider->get($payment);
        $details['buyer'] = $this->customerDetailsProvider->get($payment);

        return $details;
    }
}
