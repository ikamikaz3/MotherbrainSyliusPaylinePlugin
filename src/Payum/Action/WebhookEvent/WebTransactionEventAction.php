<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action\WebhookEvent;

use http\Exception\RuntimeException;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApiAwareTrait;
use Motherbrain\SyliusPaylinePlugin\Payum\Request\WebhookEvent\WebTransactionEvent;
use PaylineWebPayment\ServiceType\Get;
use PaylineWebPayment\StructType\GetWebPaymentDetailsRequest;
use PaylineWebPayment\StructType\Payment;
use PaylineWebPayment\StructType\PrivateData;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetToken;
use Payum\Core\Request\Notify;
use Webmozart\Assert\Assert;

final class WebTransactionEventAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use PaylineApiAwareTrait;

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $token = $this->getTokenHashFromPayment($request->getModel());

        $this->notifyWithToken($token);
    }

    private function notifyWithToken(string $token): void
    {
        $getToken = new GetToken($token);

        $this->gateway->execute($getToken);

        $this->gateway->execute(new Notify($getToken->getToken()));
    }

    private function getTokenHashFromPayment(string $token): string
    {
        $get = new Get($this->api->getWsdlOptions());

        $response = $get->getWebPaymentDetails(new GetWebPaymentDetailsRequest($this->api->getVersion(), $token));

        $privateDataList = $response->getPrivateDataList()?->getPrivateData();

        /**
         * @var int $index
         * @var PrivateData $privateData
         */
        foreach ($privateDataList as $privateData) {
            if ($privateData->getKey() === 'token_hash') {
                return $privateData->getValue();
            }
        }

        throw new RuntimeException('token_hash metadata not found');
    }

    public function supports($request): bool
    {
        return $request instanceof WebTransactionEvent;
    }
}
