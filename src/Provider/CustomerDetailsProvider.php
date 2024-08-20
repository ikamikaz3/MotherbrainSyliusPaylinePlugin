<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Provider;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class CustomerDetailsProvider implements DetailsProviderInterface
{
    public function get(PaymentInterface $payment): array
    {
        /** @var OrderInterface $order */
        $order = $payment->getOrder();
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        $buyerDetails = [];

        $buyerDetails['email'] = $customer->getEmail();
        $buyerDetails['firstname'] = $customer->getFirstName();
        $buyerDetails['lastname'] = $customer->getLastName();

        return $buyerDetails;
    }
}
