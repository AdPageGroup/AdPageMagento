<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\Data\EventInterface;
use AdPage\GTM\DataLayer\Tag\Customer\Customer;

class UserData implements EventInterface
{
    private Customer $customer;

    /**
     * @param Customer $cartItems
     */
    public function __construct(
        Customer $customer
    ) {
        $this->customer = $customer;
    }

    /**
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function get(): array
    {
        return [
            'meta' => [
                'cacheable' => false,
            ],
            'event' => 'trytagging_user_data',
            'customer' => $this->customer->get()
        ];
    }
}
