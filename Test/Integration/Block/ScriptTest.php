<?php declare(strict_types=1);

namespace AdPage\GTM\Test\Integration\Block;

use AdPage\GTM\Test\Integration\PageTestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\Layout\AssertContainerInLayout;

/**
 * @magentoAppArea frontend
 */
class ScriptTest extends PageTestCase
{
    use AssertContainerInLayout;

    /**
     * @magentoConfigFixture current_store GTM/settings/enabled 1
     * @magentoConfigFixture current_store GTM/settings/config var ADPAGE_TEST_CONFIG = true
     * @magentoConfigFixture current_store GTM/settings/serverside_gtm_url gtm.tryforwarder.com
     */
    public function testValidBlockContent()
    {
        $this->assertEnabledFlagIsWorking();

        $this->layout->getUpdate()->addPageHandles(['empty', '1column']);
        $this->layout->generateXml();

        $this->dispatch('/');

        $this->assertContainerInLayout('before.body.end');
        $this->assertStringContainsString('AdPage_GTM', $this->layout->getUpdate()->asString());

        $body = $this->getResponse()->getBody(); // @phpstan-ignore-line
        $this->assertTrue((bool)strpos($body, 'https://gtm.tryforwarder.com'), 'Script not found in HTML body: ' . $body);
        $this->assertTrue((bool)strpos($body, 'var ADPAGE_TEST_CONFIG = true'), 'Config script found in HTML head: ' . $body);
        $this->assertTrue((bool)strpos($body, 'window.AdPage_PLACED_BY_PLUGIN = true'), 'Did not found window settings: ' . $body);
    }

    /**
     * @magentoConfigFixture current_store GTM/settings/enabled 1
     * @magentoConfigFixture current_store GTM/settings/config var ADPAGE_TEST_CONFIG = true
     * @magentoConfigFixture current_store GTM/settings/serverside_gtm_url gtm.tryforwarder.com
     * @magentoConfigFixture current_store GTM/settings/choose_script_placement 1
     */
    public function testDisableScriptPlacement()
    {
        $this->assertEnabledFlagIsWorking();

        $this->layout->getUpdate()->addPageHandles(['empty', '1column']);
        $this->layout->generateXml();

        $this->dispatch('/');

        $this->assertContainerInLayout('before.body.end');
        $this->assertStringContainsString('AdPage_GTM', $this->layout->getUpdate()->asString());

        $body = $this->getResponse()->getBody(); // @phpstan-ignore-line
        $this->assertTrue(!(bool)strpos($body, 'https://gtm.tryforwarder.com'), 'Script found in HTML body: ' . $body);
        $this->assertTrue(!(bool)strpos($body, 'var ADPAGE_TEST_CONFIG = true'), 'Config script found in HTML head: ' . $body);
        $this->assertTrue((bool)strpos($body, 'window.AdPage_PLACED_BY_PLUGIN = false'), 'Did not found window settings: ' . $body);
    }
}
