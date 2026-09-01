<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApiAwareTrait;
use Motherbrain\SyliusPaylinePlugin\Payum\Request\RenderPaylineJs;
use PaylineWebPayment\ServiceType\_Do;
use PaylineWebPayment\StructType\Buyer;
use PaylineWebPayment\StructType\DoWebPaymentRequest;
use PaylineWebPayment\StructType\Order;
use PaylineWebPayment\StructType\Payment;
use PaylineWebPayment\StructType\PrivateData;
use PaylineWebPayment\StructType\PrivateDataList;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Generic;
use Payum\Core\Request\Sync;
use Payum\Core\Security\TokenInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;
    use PaylineApiAwareTrait;

    private function createApiRequest(TokenInterface $token, ArrayObject $model): DoWebPaymentRequest
    {
        /** @var array<string> $paymentDetails */
        $paymentDetails = $model->offsetGet('payment');

        $payment = new Payment(
            currency: $paymentDetails['currency'],
            action: $paymentDetails['action'],
            mode: $paymentDetails['mode'],
            amount: $paymentDetails['amount'],
            contractNumber: $this->api->getContractNumber()
        );

        /** @var array<string> $orderDetails */
        $orderDetails = $model->offsetGet('order');

        $order = new Order(
            ref: $orderDetails['ref'],
            amount: $orderDetails['amount'],
            currency: $orderDetails['currency'],
            date: $orderDetails['date'],
            country: $orderDetails['country']
        );

        /** @var array<string> $buyerDetails */
        $buyerDetails = $model->offsetGet('buyer');

        $buyer = new Buyer(
            lastName: $buyerDetails['lastname'],
            firstName: $buyerDetails['firstname'],
            email: $buyerDetails['email']
        );

        $privateDataList = new PrivateDataList();

        $privateData = new PrivateData('token_hash', $token->getHash());

        $privateDataList->addToPrivateData($privateData);

        return new DoWebPaymentRequest(
            version: $this->api->getVersion(),
            payment: $payment,
            returnURL: $token->getAfterUrl(),
            cancelURL: $token->getAfterUrl(),
            order: $order,
            buyer: $buyer,
            privateDataList: $privateDataList
        );
    }

    private function render(ArrayObject $model, Generic $request): void
    {
        $token = $request->getToken();

        if (null === $token) {
            throw new \LogicException('The request token should not be null !');
        }

        $renderRequest = new RenderPaylineJs($model->offsetGet('token'));

        $this->gateway->execute($renderRequest);
    }

    /**
     * @param Capture $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $token = $request->getToken();
        Assert::notNull($token);

        if (false === $model->offsetExists('transaction')) {
            $doWebPaymentRequest = $this->createApiRequest($token, $model);

            $do = new _Do($this->api->getWsdlOptions());

            if ($do->doWebPayment($doWebPaymentRequest) !== false) {
                $response = $do->getResult();
                $result = $response->getResult();
                Assert::notNull($result);
                Assert::notNull($response->getToken());

                $model->offsetSet('token', $response->getToken());

                $this->gateway->execute(new Sync($model));

                $this->render($model, $request);
            } else {
                // _Do::doWebPayment() saves the fault under its fully-qualified
                // __METHOD__, not the bare method name.
                $soapFault = $do->getLastErrorForMethod(_Do::class . '::doWebPayment');
                dump($soapFault);

                // Do NOT let this fall through silently: without a 'token' being set
                // on the model, the widget is never rendered and Payum's capture
                // controller will redirect straight to the "after capture" URL as if
                // nothing happened, which then gets treated downstream as a payment
                // in progress even though Payline was never actually reached.
                throw new \RuntimeException(
                    sprintf(
                        'Payline "doWebPayment" call failed: %s',
                        null !== $soapFault ? $soapFault->getMessage() : 'unknown error',
                    ),
                    0,
                    $soapFault
                );
            }
        } else {
            $this->processNotNew($model);
        }
    }

    private function processNotNew(ArrayObject $model): void
    {
        $this->gateway->execute(new Sync($model));
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
