<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Request;

use Payum\Core\Request\Convert;
use Payum\Core\Request\Generic;
use Payum\Core\Security\TokenInterface;

final class ResolveNotificationType extends Convert
{
    public function __construct(TokenInterface $token = null)
    {
        parent::__construct(null, EventWrapperInterface::class, $token);
    }
}
