<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\Plugin;

use Magento\Catalog\Block\Product\ListProduct as ListProductBlock;
use PHPUnit\Framework\TestCase;
use AdPage\GTM\Plugin\GetProductsFromCategoryBlockPlugin;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertInterceptorPluginIsRegistered;

/**
 * @magentoAppArea frontend
 */
class GetProductsFromCategoryBlockPluginTest extends TestCase
{
    use AssertInterceptorPluginIsRegistered;

    public function testIfPluginIsRegisterd()
    {
        $this->assertInterceptorPluginIsRegistered(
            ListProductBlock::class,
            GetProductsFromCategoryBlockPlugin::class,
            'AdPage_GTM::getProductsFromCategoryBlockPlugin'
        );
    }
}
