<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Provider;

use Sylius\Component\Core\Model\PaymentInterface;

interface DetailsProviderInterface
{
    public function get(PaymentInterface $payment): array;
}
