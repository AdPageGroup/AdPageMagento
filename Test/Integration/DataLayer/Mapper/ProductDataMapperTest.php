<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\DataLayer\Mapper;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use AdPage\GTM\DataLayer\Mapper\ProductDataMapper;
use AdPage\GTM\Test\Integration\FixtureTrait\CreateProduct;

class ProductDataMapperTest extends TestCase
{
    use CreateProduct;

    public function testMapByProduct()
    {
        $product = $this->createProducts()[0];
        $productDataMapper = ObjectManager::getInstance()->get(ProductDataMapper::class);
        $productData = $productDataMapper->mapByProduct($product);
        $this->assertEquals('Product 1', $productData['item_name']);
        $this->assertEquals('product1', $productData['item_id']);
    }
}
