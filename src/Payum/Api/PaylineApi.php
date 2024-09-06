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

    public const NOTIFICATION_TYPE_WEBTRS = 'WEBTRS';

    public const ORDER_ORIGIN = 'E_COM';

    /**
     * See : https://docs.monext.fr/display/DT/Codes+-+deliveryMode
     */
    public const DELIVERY_MODE_STORE_PICKUP = 1;
    public const DELIVERY_MODE_PICKUP_POINT = 2;
    public const DELIVERY_MODE_STATION_PICKUP = 3;
    public const DELIVERY_MODE_MAIL_DELIVERY = 4;
    public const DELIVERY_MODE_DIGITAL_GOOD = 5;
    public const DELIVERY_MODE_BILLING_ADDRESS = 6;
    public const DELIVERY_MODE_VERIFIED_ADDRESS = 7;
    public const DELIVERY_MODE_OTHER_ADDRESS = 8;
    public const DELIVERY_MODE_EVENT_TICKET = 9;
    public const DELIVERY_MODE_LOCKER = 10;
    public const DELIVERY_MODE_OTHER = 999;

    public function __construct(
        private readonly string $merchantId,
        private readonly string $merchantAccessKey,
        private readonly string $contractNumber,
        private readonly string $mode
    ) {
    }

    private function getBaseUri(): string
    {
        if (self::MODE_HOMOLOGATION === $this->mode) {
            return 'https://homologation.payline.com/V4/services/WebPaymentAPI';
        } elseif (self::MODE_PRODUCTION === $this->mode) {
            return 'https://services.payline.com/V4/services/WebPaymentAPI';
        } else {
            throw new \RuntimeException('Mode "' . $this->mode . '" is not supported.');
        }
    }

    /**
     * @return array<string|string[]|int>
     */
    public function getWsdlOptions(): array
    {
        return [
            SoapClientInterface::WSDL_URL => $this->getBaseUri() . '?wsdl',
            SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            SoapClientInterface::WSDL_LOGIN => $this->getMerchantId(),
            SoapClientInterface::WSDL_PASSWORD => $this->getMerchantAccessKey(),
            SoapClientInterface::WSDL_LOCATION => $this->getBaseUri(),
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

    public function getContractNumber(): string
    {
        return $this->contractNumber;
    }
}
