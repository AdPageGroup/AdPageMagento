<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\Plugin;

use Magento\Customer\CustomerData\Customer;
use PHPUnit\Framework\TestCase;
use AdPage\GTM\Plugin\AddDataToCustomerSection;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertInterceptorPluginIsRegistered;

/**
 * @magentoAppArea frontend
 */
class AddDataToCustomerSectionTest extends TestCase
{
    use AssertInterceptorPluginIsRegistered;

    public function testIfPluginIsRegisterd()
    {
        $this->assertInterceptorPluginIsRegistered(
            Customer::class,
            AddDataToCustomerSection::class,
            'Yireo_GoogleTagManager2::addDataToCustomerSection'
        );
    }
}
