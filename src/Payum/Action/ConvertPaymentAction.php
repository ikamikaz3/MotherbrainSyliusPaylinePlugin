<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Alcohol\ISO4217;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Motherbrain\SyliusPaylinePlugin\Provider\DetailsProviderInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final class ConvertPaymentAction implements ActionInterface
{
    public function __construct(private DetailsProviderInterface $detailsProvider)
    {}

    /**
     * @param Convert $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);
        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = $this->detailsProvider->get($payment);

        $request->setResult($details);
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
