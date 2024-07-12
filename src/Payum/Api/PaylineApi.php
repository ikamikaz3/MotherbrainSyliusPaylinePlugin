<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Api;

use PaylineWebPayment\ClassMap;
use WsdlToPhp\PackageBase\SoapClientInterface;

final class PaylineApi
{
    public const MODE_HOMOLOGATION = 'HOMO';
    public const MODE_PRODUCTION = 'PROD';

    public const API_VERSION = '34';

    public const ACTION_AUTH_CAPTURE = '101';

    public const PAYMENT_MODE_FULL = 'CPT';

    public function __construct(
        private readonly string $merchantId,
        private readonly string $merchantAccessKey
    ) {
    }

    /**
     * @return array<string|string[]|int>
     */
    public function getWsdlOptions(): array
    {
        return [
            SoapClientInterface::WSDL_URL => 'https://services.payline.com/V4/services/WebPaymentAPI?wsdl',
            SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            SoapClientInterface::WSDL_LOGIN => $this->getMerchantId(),
            SoapClientInterface::WSDL_PASSWORD => $this->getMerchantAccessKey(),
            SoapClientInterface::WSDL_LOCATION => 'https://services.payline.com/V4/services/WebPaymentAPI',
            SoapClientInterface::WSDL_AUTHENTICATION => 0,
            SoapClientInterface::WSDL_TRACE => 1,
        ];
    }

    public function getVersion(): string
    {
        return self::API_VERSION;
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
