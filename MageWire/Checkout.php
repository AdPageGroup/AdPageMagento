<?php
declare(strict_types=1);

namespace AdPage\GTM\MageWire;

use Magento\Checkout\Model\Session as CheckoutSession;
use AdPage\GTM\DataLayer\Event\AddPaymentInfo;
use AdPage\GTM\DataLayer\Event\AddShippingInfo;
use AdPage\GTM\DataLayer\Event\BeginCheckout;

class Checkout extends Component
{
    protected $listeners = [
        'shipping_method_selected' => 'triggerShippingMethod',
        'payment_method_selected' => 'triggerPaymentMethod',
    ];
    private CheckoutSession $checkoutSession;
    private BeginCheckout $beginCheckout;
    private AddShippingInfo $addShippingInfo;
    private AddPaymentInfo $addPaymentInfo;

    public function __construct(
        CheckoutSession $checkoutSession,
        BeginCheckout $beginCheckout,
        AddShippingInfo $addShippingInfo,
        AddPaymentInfo $addPaymentInfo
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->beginCheckout = $beginCheckout;
        $this->addShippingInfo = $addShippingInfo;
        $this->addPaymentInfo = $addPaymentInfo;
    }

    public function triggerBeginCheckout()
    {
        $this->dispatchBrowserEvent('ga:trigger-event', $this->beginCheckout->get());
    }

    public function triggerShippingMethod()
    {
        $this->dispatchBrowserEvent('ga:trigger-event', $this->addShippingInfo->get());
    }

    public function triggerPaymentMethod()
    {
        $this->addPaymentInfo->setCartId((int) $this->checkoutSession->getQuote()->getId());
        $this->addPaymentInfo->setPaymentMethod((string) $this->checkoutSession->getQuote()->getPayment()->getMethod());
        $this->dispatchBrowserEvent('ga:trigger-event', $this->addPaymentInfo->get());
    }
}
