<?php

declare(strict_types=1);

namespace Motherbrain\SyliusPaylinePlugin\Payum\Action;

use Motherbrain\SyliusPaylinePlugin\Payum\Request\RenderPaylineJs;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\RenderTemplate;
use Webmozart\Assert\Assert;

final class RenderPaylineJsAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function __construct(private readonly string $templateName)
    {
    }

    /**
     * @param RenderPaylineJs $request
     * @return void
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $paymentToken = $request->getModel();
        Assert::notNull($paymentToken);

        $renderTemplate = new RenderTemplate($this->templateName, [
            'paymentToken' => $paymentToken,
        ]);

        $this->gateway->execute($renderTemplate);

        throw new HttpResponse($renderTemplate->getResult());
    }

    public function supports($request): bool
    {
        return
            $request instanceof RenderPaylineJs &&
            is_string($request->getModel());
    }
}
