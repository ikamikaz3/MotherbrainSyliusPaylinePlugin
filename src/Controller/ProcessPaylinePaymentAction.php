<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Payum\Core\Payum;
use SM\Factory\FactoryInterface as StateMachineFactory;
use SM\SMException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class ProcessPaylinePaymentAction
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private StateMachineFactory $smFactory,
        private EntityManagerInterface $em,
        private RouterInterface $router
    ) {
    }

    /**
     * @throws SMException
     */
    public function __invoke(Request $request): RedirectResponse
    {
        /** @var PaymentInterface $payment */
        $payment = $this->paymentRepository->find($request->get('paymentId'));

        $sm = $this->smFactory->get($payment, PaymentTransitions::GRAPH);

        /** @var array<string, mixed> $details */
        $details = $payment->getDetails();

        // A 'token' is only ever set on the payment details once Payline actually
        // accepted the "doWebPayment" call (see CaptureAction). If it's missing
        // here, no transaction was ever initiated on Payline's side (e.g. the SOAP
        // call errored out) — don't pretend the payment went through.
        if (false === isset($details['token'])) {
            if ($sm->can(PaymentTransitions::TRANSITION_FAIL)) {
                $sm->apply(PaymentTransitions::TRANSITION_FAIL);

                $this->em->flush();
            }

            return new RedirectResponse($this->router->generate('sylius_shop_order_show', [
                'tokenValue' => $payment->getOrder()?->getTokenValue(),
            ]));
        }

        if (BasePaymentInterface::STATE_PROCESSING !== $payment->getState()) {
            Assert::true($sm->can(PaymentTransitions::TRANSITION_PROCESS));

            $sm->apply(PaymentTransitions::TRANSITION_PROCESS);

            $this->em->flush();
        }

        return new RedirectResponse($this->router->generate('sylius_shop_order_thank_you'));
    }
}
