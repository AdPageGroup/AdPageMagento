<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Order;
use AdPage\GTM\Config\Config;

class PurchaseWebhookEvent
{
    /**
     * @var Json $json
     */
    private $json;

    /**
     * @var ClientFactory $clientFactory
     */
    private $clientFactory;

    /**
     * @var Config $config
     */
    private $config;

    public function __construct(
        Json            $json,
        ClientFactory   $clientFactory,
        Config          $config,
    ) {
        $this->json = $json;
        $this->clientFactory = $clientFactory;
        $this->config = $config;
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

    public function purchase(Order $order, array $additionalInfo = [])
    {
        $data = [
            'user_data' => $order,
            'ecommerce' => $order,
        ] + $additionalInfo;
        $this->call('purchase', $data);
    }
}
