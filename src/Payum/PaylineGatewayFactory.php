<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum;

use Motherbrain\SyliusPaylinePlugin\Payum\Action\CaptureAction;
use Motherbrain\SyliusPaylinePlugin\Payum\Action\ConvertPaymentAction;
use Motherbrain\SyliusPaylinePlugin\Payum\Action\RenderPaylineJs;
use Motherbrain\SyliusPaylinePlugin\Payum\Action\RenderPaylineJsAction;
use Motherbrain\SyliusPaylinePlugin\Payum\Action\StatusAction;
use Motherbrain\SyliusPaylinePlugin\Payum\Action\SyncAction;
use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Webmozart\Assert\Assert;

final class PaylineGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'payline',
            'payum.factory_title' => 'Payline',
            'payum.template.render_payline' => '@MotherbrainSyliusPaylinePlugin/Action/render_payline.html.twig',
            // Actions
            'payum.action.status' => new StatusAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.render_payline' => function (ArrayObject $config) {
                Assert::true($config->offsetExists('payum.template.render_payline'));

                /** @var string $templateName */
                $templateName = $config['payum.template.render_payline'];
                Assert::stringNotEmpty($templateName);

                return new RenderPaylineJsAction(
                    $templateName
                );
            }
        ]);

        $config['payum.api'] = function (ArrayObject $config) {
            Assert::true($config->offsetExists('merchantId'));
            Assert::true($config->offsetExists('merchantAccessKey'));

            /** @var string $merchantId */
            $merchantId = $config['merchantId'];
            Assert::stringNotEmpty($merchantId);

            /** @var string $merchantAccessKey */
            $merchantAccessKey = $config['merchantAccessKey'];
            Assert::stringNotEmpty($merchantAccessKey);

            return new PaylineApi($merchantId, $merchantAccessKey);
        };
    }
}
