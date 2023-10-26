<?php declare(strict_types=1);

namespace AdPage\GTM\Config;

use Magento\Cookie\Helper\Cookie as CookieHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use AdPage\GTM\Model\Config\Source\ViewCartOccurancesOptions;

class Config implements ArgumentInterface
{
    private ScopeConfigInterface $scopeConfig;
    private CookieHelper $cookieHelper;
    private StoreManagerInterface $storeManager;
    private AppState $appState;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param CookieHelper $cookieHelper
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CookieHelper $cookieHelper,
        AppState $appState
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->cookieHelper = $cookieHelper;
        $this->appState = $appState;
    }

    /**
     * Check whether the module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        $enabled = (bool)$this->getModuleConfigValue('enabled', false);
        if (false === $enabled) {
            return false;
        }

        return true;
    }

    /**
     *
     * Get the Google tag manager url. Defaults to googletagmanager.com. when field is filled return that url.
     *
     * @return string
     */
    public function getGoogleTagmanagerUrl(): string
    {
        return $this->getModuleConfigValue(
            'serverside_gtm_url',
            ''
        );
    }

    /**
     * Check whether the module is in debugging mode
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return (bool)$this->getModuleConfigValue('debug');
    }

    /**
     * Return the GA ID
     *
     * @return string
     */
    public function getConfig(): string
    {
        return (string)$this->getModuleConfigValue('config');
    }

    /**
     * @return string[]
     */
    public function getCustomerEavAttributeCodes(): array
    {
        return [
            'email',
            'firstname',
            'middlename',
            'lastname',
            'dob',
            'created_at'
        ];
    }

    /**
     * @return string
     */
    public function getStoreName(): string
    {
        $storeName = (string)$this->getConfigValue('general/store_information/name');
        if (!empty($storeName)) {
            return $storeName;
        }

        return (string)$this->storeManager->getDefaultStoreView()->getName();
    }

    /**
     * Return a configuration value
     *
     * @param string $key
     * @param null $defaultValue
     *
     * @return mixed|null
     */
    public function getModuleConfigValue(string $key, $defaultValue = null)
    {
        return $this->getConfigValue('GTM/settings/' . $key, $defaultValue);
    }

    /**
     * Return a configuration value
     *
     * @param string $key
     * @param null $defaultValue
     *
     * @return mixed|null
     */
    public function getConfigValue(string $key, $defaultValue = null)
    {
        try {
            $value = $this->scopeConfig->getValue(
                $key,
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()
            );
        } catch (NoSuchEntityException $e) {
            return $defaultValue;
        }

        if (empty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * @return bool
     */
    private function isDeveloperMode(): bool
    {
        return $this->appState->getMode() === AppState::MODE_DEVELOPER;
    }
}
