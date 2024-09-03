<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin;

use Motherbrain\SyliusPaylinePlugin\DependencyInjection\Compiler\PayumStoragePaymentAliaser;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MotherbrainSyliusPaylinePlugin extends AbstractBundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new PayumStoragePaymentAliaser());

        parent::build($container);
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
