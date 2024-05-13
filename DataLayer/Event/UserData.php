<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\Data\EventInterface;
use AdPage\GTM\DataLayer\Tag\PageTitle;
use AdPage\GTM\DataLayer\Tag\PageType;

class UserData implements EventInterface
{
    private PageTitle $pageTitle;
    private PageType $pageType;

    /**
     * @param Customer $cartItems
     */
    public function __construct(
        PageTitle $pageTitle,
        PageType $pageType
    ) {
        $this->pageTitle = $pageTitle;
        $this->pageType = $pageType;
    }

    /**
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function get(): array
    {
        return [
            'event' => 'trytagging_user_data',
            'page' => [
                'title' => $this->pageTitle->get(),
                'type' => $this->pageType->get()
            ]
        ];
    }
}
