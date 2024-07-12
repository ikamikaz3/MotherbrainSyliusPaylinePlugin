<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Request;

use Payum\Core\Request\Generic;

final class RenderPaylineJs extends Generic
{
    public function __construct(string $paymentToken)
    {
        parent::__construct($paymentToken);
    }

    public function getPaymentToken(): ?string
    {
        $model = $this->getModel();
        if (false === is_string($model)) {
            return null;
        }

        return $model;
    }
}
