<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Tag\Customer;

use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\Data\TagInterface;
use Magento\Customer\Model\Session;

class Customer implements TagInterface
{
    private Session $customerSession;

    public function __construct(
        Session $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function get(): array
    {
        $customer = $this->customerSession->getCustomerId();

        return $customer;
    }
}
