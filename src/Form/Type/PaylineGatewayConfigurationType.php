<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PaylineGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('merchantId', TextType::class, [
                'label' => 'motherbrain_sylius_payline_plugin.ui.merchant_id',
            ])
            ->add('merchantAccessKey', TextType::class, [
                'label' => 'motherbrain_sylius_payline_plugin.ui.merchant_access_key'
            ]);
    }
}
