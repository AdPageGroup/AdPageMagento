<?php declare(strict_types=1);

namespace AdPage\GTM\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use AdPage\GTM\Api\CheckoutSessionDataProviderInterface;
use AdPage\GTM\DataLayer\Event\Purchase as PurchaseEvent;
use AdPage\GTM\DataLayer\Event\PurchaseWebhookEvent;
use Psr\Log\LoggerInterface;
use Exception;

class TriggerPurchaseDataLayerEvent implements ObserverInterface
{
    private CheckoutSessionDataProviderInterface $checkoutSessionDataProvider;
    private PurchaseEvent $purchaseEvent;
    private LoggerInterface $logger;
    private PurchaseWebhookEvent $webhookEvent;

    public function __construct(
        CheckoutSessionDataProviderInterface $checkoutSessionDataProvider,
        PurchaseEvent $purchaseEvent,
        PurchaseWebhookEvent $webhookEvent,
        LoggerInterface $logger
    ) {
        $this->checkoutSessionDataProvider = $checkoutSessionDataProvider;
        $this->purchaseEvent = $purchaseEvent;
        $this->logger = $logger;
        $this->webhookEvent = $webhookEvent;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $this->logger->critical('TriggerPurchaseDataLayerEvent::execute(): has changed ');
        $this->checkoutSessionDataProvider->add(
            'purchase_event',
            $this->purchaseEvent->setOrder($order)->get()
        );

        $this->logger->critical('TriggerPurchaseWebhook::execute(): has changed ' . $order->dataHasChangedFor('total_paid'));
        $this->logger->critical('TriggerPurchaseWebhook::execute(): has grand total ' . $order->getGrandTotal());
        $this->logger->critical('TriggerPurchaseWebhook::execute(): total paid ' . $order->getTotalPaid());

        if (!$order->dataHasChangedFor('total_paid') || $order->getGrandTotal() > $order->getTotalPaid()) {
            return;
        }

        try {
            $this->webhookEvent->purchase($order, []);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
