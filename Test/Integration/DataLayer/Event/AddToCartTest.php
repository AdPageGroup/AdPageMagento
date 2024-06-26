<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\DataLayer\Event;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use AdPage\GTM\DataLayer\Event\AddToCart;
use AdPage\GTM\Test\Integration\FixtureTrait\CreateProduct;

/**
 * @magentoAppArea frontend
 */
class AddToCartTest extends TestCase
{
    use CreateProduct;

    /**
     * @magentoConfigFixture current_store GTM/settings/enabled 1
     * @magentoConfigFixture current_store GTM/settings/serverside_gtm_url gtm.tryforwarder.com
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     */
    public function testValidDataLayerWithCart()
    {
        $product = $this->createProduct(1);
        $addToCartEvent = ObjectManager::getInstance()->get(AddToCart::class);
        $data = $addToCartEvent->setProduct($product)->get();
        $this->assertCount(1, $data['ecommerce']['items']);
    }
}
