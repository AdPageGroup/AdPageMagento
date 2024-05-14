<?php

declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\Data\EventInterface;
use AdPage\GTM\DataLayer\Tag\PageTitle;
use AdPage\GTM\DataLayer\Tag\PageType;
use AdPage\GTM\DataLayer\Tag\Store\CurrentStore;

class UserData implements EventInterface
{
    private PageTitle $pageTitle;
    private PageType $pageType;
    private CurrentStore $currentStore;

    /**
     * @param Customer $cartItems
     */
    public function __construct(
        PageTitle $pageTitle,
        PageType $pageType,
        CurrentStore $currentStore
    ) {
        $this->pageTitle = $pageTitle;
        $this->pageType = $pageType;
        $this->currentStore = $currentStore;
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
            ],
            'store' => $this->currentStore->get(),
        ];
    }
}
