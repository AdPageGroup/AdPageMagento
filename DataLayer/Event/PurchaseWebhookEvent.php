<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Order;
use AdPage\GTM\DataLayer\Tag\Order\OrderItems;
use Magento\Sales\Api\Data\OrderInterface;
use AdPage\GTM\Util\PriceFormatter;
use AdPage\GTM\Config\Config;

class PurchaseWebhookEvent
{
    private $json;
    private $clientFactory;
    private $config;
    private $orderItems;
    private $priceFormatter;

    public function __construct(
        Json            $json,
        ClientFactory   $clientFactory,
        OrderItems      $orderItems,
        Config          $config,
        PriceFormatter  $priceFormatter
    ) {
        $this->json = $json;
        $this->clientFactory = $clientFactory;
        $this->orderItems = $orderItems;
        $this->config = $config;
        $this->priceFormatter = $priceFormatter;
    }

    private function call($event, $data)
    {
        $data['event'] = $event;
        $client = $this->clientFactory->create();
        $client->addHeader('Content-Type', 'application/json');
        $client->addHeader('Accept', 'application/json');

        try {

            $url = $this->config->getGoogleTagmanagerUrl();
            if (!empty($url)) {
                $client->post($url, $this->json->serialize($data));
            }
        } catch (\Exception $e) {
        }
        return $client->getStatus() == 200;
    }

    public function purchase(OrderInterface $order, array $additionalInfo = [])
    {
        $data = [
            'user_data' => [],
            'ecommerce' => [
                'transaction_id' => $order->getIncrementId(),
                'affiliation' => $this->config->getStoreName(),
                'currency' => $order->getOrderCurrencyCode(),
                'value' => $this->priceFormatter->format((float)$order->getGrandTotal()),
                'tax' => $this->priceFormatter->format((float)$order->getTaxAmount()),
                'shipping' => $this->priceFormatter->format((float)$order->getShippingInclTax()),
                'coupon' => $order->getCouponCode(),
                'items' => $this->orderItems->setOrder($order)->get()
            ]
        ] + $additionalInfo;
        $this->call('purchase', $data);
    }
}
