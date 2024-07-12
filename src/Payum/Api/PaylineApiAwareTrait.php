<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Api;

use Payum\Core\Exception\UnsupportedApiException;

trait PaylineApiAwareTrait
{
    private PaylineApi $api;

    public function setApi($api): void
    {
        if (!$api instanceof PaylineApi) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . PaylineApi::class);
        }

        $this->api = $api;
    }
}
