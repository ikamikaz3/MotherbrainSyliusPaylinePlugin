<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Form\Type;

use Motherbrain\SyliusPaylinePlugin\Payum\Api\PaylineApi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PaylineApiModeChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                PaylineApi::MODE_PRODUCTION => PaylineApi::MODE_PRODUCTION,
                PaylineApi::MODE_HOMOLOGATION => PaylineApi::MODE_HOMOLOGATION
            ]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
