<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Tag\Store;

use AdPage\GTM\Api\Data\TagInterface;
use Magento\Store\Model\StoreManagerInterface;

class CurrentStore implements TagInterface
{
    private $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager,
    ) {
        $this->storeManager = $storeManager;
    }

    public function get()
    {
        return $this->storeManager->getStore()->getCurrentUrl();
    }
}
