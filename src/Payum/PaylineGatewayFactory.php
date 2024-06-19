<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum;

use Doctrine\ORM\Query\Parameter;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class PaylineGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'payline',
            'payum.factory_title' => 'Payline'
        ]);

        $config['payum.api'] = function (ArrayObject $config) {
            return new PaylineApi($config['merchantId'], $config['merchantAccessKey']);
        };
    }
}
