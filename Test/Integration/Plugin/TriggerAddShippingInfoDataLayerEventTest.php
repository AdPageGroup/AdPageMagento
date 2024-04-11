<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\Plugin;

use Magento\Checkout\Api\ShippingInformationManagementInterface;
use PHPUnit\Framework\TestCase;
use AdPage\GTM\Plugin\TriggerAddShippingInfoDataLayerEvent;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertInterceptorPluginIsRegistered;

/**
 * @magentoAppArea webapi_rest
 */
class TriggerAddShippingInfoDataLayerEventTest extends TestCase
{
    use AssertInterceptorPluginIsRegistered;

    public function testIfPluginIsRegisterd()
    {
        $this->assertInterceptorPluginIsRegistered(
            ShippingInformationManagementInterface::class,
            TriggerAddShippingInfoDataLayerEvent::class,
            'AdPage_GTM::triggerAddShippingInfoDataLayerEvent'
        );
    }
}
