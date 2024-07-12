<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Alcohol\ISO4217;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class ConvertPaymentAction implements ActionInterface
{
    /**
     * @param Convert $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();
        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $iso4217 = new ISO4217();

        $currency = $iso4217->getByAlpha3($payment->getCurrencyCode());

        $paymentDetails = [];

        $paymentDetails['amount'] = (string)$payment->getAmount();
        $paymentDetails['currency'] = $currency['numeric'];
        $paymentDetails['action'] = PaylineApi::ACTION_AUTH_CAPTURE;
        $paymentDetails['mode'] = PaylineApi::PAYMENT_MODE_FULL;
        $paymentDetails['contractNumber'] = '1243256';

        $details->offsetSet('payment', $paymentDetails);

        $orderDetails = [];


        $orderDetails['amount'] = (string)$payment->getAmount();
        $orderDetails['currency'] = $currency['numeric'];
        $orderDetails['ref'] = $order->getNumber();
        Assert::notNull($checkoutCompletedAt = $order->getCheckoutCompletedAt());
        $orderDetails['date'] = $checkoutCompletedAt->format('d/m/Y H:i');

        $details->offsetSet('order', $orderDetails);

        $buyerDetails = [];

        Assert::notNull($customer = $order->getCustomer());
        $buyerDetails['email'] = $customer->getEmail();
        $buyerDetails['firstname'] = $customer->getFirstName();
        $buyerDetails['lastname'] = $customer->getLastName();

        $details->offsetSet('buyer', $buyerDetails);

        $request->setResult((array) $details);
    }


    public function supports($request): bool
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
            ;
    }
}
