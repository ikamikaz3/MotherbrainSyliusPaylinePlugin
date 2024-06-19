<?php

declare(strict_types=1);

namespace Tests\Motherbrain\SyliusPaylinePlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\When;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Tests\Motherbrain\SyliusPaylinePlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;

class ManagingPaymentMethodsContext implements Context
{
    public function __construct(private CreatePageInterface $createPage)
    {
    }

    #[Given('/^I want to create a new Payline payment method$/')]
    public function iCreateANewPaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'payline']);
    }

    #[When('I configure it with test payline gateway data :merchantId, :merchantAccessKey')]
    public function iConfigureItWithTestPaylineGatewayData(string $merchantId, string $merchantAccessKey): void
    {
        $this->createPage->setMerchantId($merchantId);
        $this->createPage->setMerchantAccessKey($merchantAccessKey);
    }
}
