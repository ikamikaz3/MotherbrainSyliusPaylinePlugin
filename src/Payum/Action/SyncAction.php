<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApiAwareTrait;
use PaylineWebPayment\ServiceType\Get;
use PaylineWebPayment\StructType\GetWebPaymentDetailsRequest;
use PaylineWebPayment\StructType\GetWebPaymentDetailsResponse;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Sync;
use Webmozart\Assert\Assert;

final class SyncAction implements ActionInterface, ApiAwareInterface
{
    use PaylineApiAwareTrait;

    /**
     * @param Sync $request
     * @return void
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        Assert::true($model->offsetExists('token'));

        $get = new Get($this->api->getWsdlOptions());

        /** @var string $token */
        $token = $model->offsetGet('token');
        if (false !== $get->getWebPaymentDetails(new GetWebPaymentDetailsRequest($this->api->getVersion(), $token))) {
            /** @var GetWebPaymentDetailsResponse $response */
            $response = $get->getResult();
            $result = $response->getResult();
            Assert::notNull($result);

            $model->exchangeArray(json_decode(json_encode($response), true));
            // Set the token again because it's not present in the Payline response.
            $model->offsetSet('token', $token);
        } else {
            dump($get->getLastRequestHeaders());
            dump($get->getLastRequest());
            dump($get->getLastResponse());
            dump($get->getLastError());
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof Sync &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
