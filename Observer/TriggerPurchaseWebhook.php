<?php declare(strict_types=1);

namespace AdPage\GTM\Observer;

use AdPage\GTM\DataLayer\Event\PurchaseWebhookEvent;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;

class OrderSaveCommitAfter implements ObserverInterface
{
    private $webhookEvent;
    private $debugger;
    private $logger;

    public function __construct(
        PurchaseWebhookEvent $webhookEvent,
        Debugger $debugger,
        LoggerInterface $logger
    ) {
        $this->webhookEvent = $webhookEvent;
        $this->debugger = $debugger;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $this->debugger->debug('OrderSaveCommitAfter::execute(): has changed ', $order->dataHasChangedFor('total_paid'));
        $this->debugger->debug('OrderSaveCommitAfter::execute(): has grand total ', $order->getGrandTotal());
        $this->debugger->debug('OrderSaveCommitAfter::execute(): total paid ', $order->getTotalPaid());

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