<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\Page;

use Magento\Customer\CustomerData\SectionPool;
use AdPage\GTM\SessionDataProvider\CustomerSessionDataProvider;
use AdPage\GTM\Test\Integration\FixtureTrait\CreateCustomer;
use AdPage\GTM\Test\Integration\PageTestCase;

class LogoutTest extends PageTestCase
{
    use CreateCustomer;

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoAppArea frontend
     * @return void
     */
    public function testLogout()
    {
        $this->createCustomer();
        $this->loginCustomer();

        $this->objectManager->get(CustomerSessionDataProvider::class)->clear();

        $this->dispatch('customer/account/logout');

        $customerSectionPool = $this->objectManager->get(SectionPool::class);
        $data = $customerSectionPool->getSectionsData(['customer']);

        $this->assertArrayHasKey('logout_event', $data['customer']['gtm_events']);
        $this->assertEquals('logout', $data['customer']['gtm_events']['logout_event']['event']);
    }
}
