<?php

declare(strict_types=1);

namespace Tests\Motherbrain\SyliusPaylinePlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\PaymentMethod\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setMerchantId(string $merchantId): void;

    public function setMerchantAccessKey(string $accessKey): void;
}
