<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use ArrayAccess;
use PaylineWebPayment\StructType\PrivateData;
use PaylineWebPayment\StructType\Transaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

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

        if ($this->isIdentifiableTransaction($model)) {
            /** @var string[] $result */
            $result = $model->offsetGet('result');
            if ($this->isCaptured($result)) {
                $request->markCaptured();
                return;
            }

            if ($this->isPending($result)) {
                $request->markPending();
                return;
            }
        }

        $request->markNew();
    }

    private function isIdentifiableTransaction(ArrayObject $model): bool
    {
        if (true === $model->offsetExists('transaction')) {
            /** @var string[] $transaction */
            $transaction = $model->offsetGet('transaction');

            return isset($transaction['id']) && $transaction['id'] !== '';
        }

        return false;
    }

    /**
     * @param string[] $result
     * @return bool
     */
    private function isCaptured(array $result): bool
    {
        return $result['code'] === '00000' && $result['shortMessage'] === 'ACCEPTED';
    }

    /**
     * @param string[] $result
     * @return bool
     */
    private function isPending(array $result): bool
    {
        return $result['code'] === '02533' && $result['shortMessage'] === 'INPROGRESS';
    }

    public function supports($request): bool
    {
        if (false === $request instanceof GetStatusInterface) {
            return false;
        }

        return $request->getModel() instanceof ArrayAccess;
    }
}
