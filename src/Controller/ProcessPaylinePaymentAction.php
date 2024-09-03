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

        if (BasePaymentInterface::STATE_PROCESSING !== $payment->getState()) {
            Assert::true($sm->can(PaymentTransitions::TRANSITION_PROCESS));

            $sm->apply(PaymentTransitions::TRANSITION_PROCESS);

            $this->em->flush();
        }

        return new RedirectResponse($this->router->generate('sylius_shop_order_thank_you'));
    }
}
