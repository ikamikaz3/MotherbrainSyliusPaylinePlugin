<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use ArrayAccess;
use PaylineWebPayment\ClassMap;
use PaylineWebPayment\StructType\GetWebPaymentDetailsRequest;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use PaylineWebPayment\ServiceType\Get;
use WsdlToPhp\PackageBase\SoapClientInterface;

final class StatusAction implements ActionInterface
{
    use ApiAwareTrait;

    /**
     * @param GetStatusInterface $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($model->offsetExists('result')) {
            $result = $model->offsetGet('result');
            if ($result['code'] === '00000' && $result['shortMessage'] === 'ACCEPTED') {
                $request->markCaptured();
                return;
            }

            if ($result['code'] === '02533' && $result['shortMessage'] === 'INPROGRESS') {
                $request->markPending();
                return;
            }
        }

        $request->markNew();
    }

    public function supports($request): bool
    {
        if (false === $request instanceof GetStatusInterface) {
            return false;
        }

        return $request->getModel() instanceof ArrayAccess;
    }
}
