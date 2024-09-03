<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\GatewayConfigInterface;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Sync;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRouteInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ResolveNextRouteAction implements GatewayAwareInterface, ActionInterface
{
    use GatewayAwareTrait;

    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * @param ResolveNextRoute $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        $request->setRouteName('motherbrain_sylius_payline_process_payline_payment_action');
        $request->setRouteParameters(['paymentId' => $payment->getId()]);
    }

    public function supports($request): bool
    {
        if (
            !$request instanceof ResolveNextRoute ||
            !$request->getFirstModel() instanceof PaymentInterface
        ) {
            return false;
        }

        /** @var PaymentInterface $model */
        $model = $request->getFirstModel();
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $model->getMethod();
        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        return $gatewayConfig->getFactoryName() === 'payline';
    }
}
