<?php declare(strict_types=1);

namespace AdPage\GTM\Plugin;

use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\LayoutInterface;
use AdPage\GTM\Exception\BlockNotFound;

class AddCustomerDetails
{
    private LayoutInterface $layout;

    public function __construct(
        LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    /**
     * @return BlockInterface
     * @throws BlockNotFound
     */
    private function getCustomerDetails()
    {
        $block = $this->layout->getBlock('AdPage_GTM.customer-details');
        
        return 'Customer Details';
    }
}
