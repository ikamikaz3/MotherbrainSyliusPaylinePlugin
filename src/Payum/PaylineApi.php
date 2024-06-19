<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum;

final class PaylineApi
{
    public function __construct(
        private string $merchantId,
        private string $merchantAccessKey
    ) {
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getMerchantAccessKey(): string
    {
        return $this->merchantAccessKey;
    }
}
