<?php declare(strict_types=1);

namespace AdPage\GTM\Model;

use AdPage\GTM\Api\OrderNotesInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class OrderNotes implements OrderNotesInterface
{
    protected $checkoutSession;

    public function __construct(CheckoutSession $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    public function saveData($jsonData)
    {
        $this->checkoutSession->setData('trytagging_marketing', $jsonData);
        return 'Data saved in checkout session';
    }
}