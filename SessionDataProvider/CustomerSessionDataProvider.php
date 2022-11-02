<?php declare(strict_types=1);

namespace Yireo\GoogleTagManager2\SessionDataProvider;

use Magento\Customer\Model\Session as CustomerSession;
use Yireo\GoogleTagManager2\Api\CustomerSessionDataProviderInterface;

class CustomerSessionDataProvider implements CustomerSessionDataProviderInterface
{
    private CustomerSession $customerSession;

    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    public function set(string $name, $value)
    {
        $gtmData = $this->get();
        $gtmData[$name] = $value;
        $this->customerSession->setYireoGtmData($gtmData);
    }

    public function append(array $data)
    {
        $gtmData = $this->get();
        $gtmData = array_merge($gtmData, $data);
        $this->customerSession->setYireoGtmData($gtmData);
    }

    public function get(): array
    {
        $gtmData = $this->customerSession->getYireoGtmData();
        if (is_array($gtmData)) {
            return $gtmData;
        }

        return [];
    }

    public function clear()
    {
        $this->customerSession->setYireoGtmData([]);
    }
}
