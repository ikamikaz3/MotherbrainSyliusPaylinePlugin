<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('motherbrain_sylius_payline_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
