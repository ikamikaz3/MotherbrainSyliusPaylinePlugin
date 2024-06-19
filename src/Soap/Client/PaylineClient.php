<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Soap\Client;

use Phpro\SoapClient\Caller\Caller;

class PaylineClient
{
    public function __construct(private Caller $caller)
    {
    }


}
