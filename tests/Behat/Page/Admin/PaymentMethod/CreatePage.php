<?php

declare(strict_types=1);

namespace Tests\Motherbrain\SyliusPaylinePlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\PaymentMethod\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function setMerchantId(string $merchantId): void
    {
        $this->getDocument()->fillField('Merchant ID', $merchantId);
    }

    public function setMerchantAccessKey(string $accessKey): void
    {
        $this->getDocument()->fillField('Merchant Access Key', $accessKey);
    }
}
