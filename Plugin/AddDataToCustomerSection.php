<?php declare(strict_types=1);

/**
 * GoogleTagManager2 plugin for Magento
 *
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace AdPage\GTM\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\CustomerData\Customer as CustomerData;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\CustomerSessionDataProviderInterface;
use AdPage\GTM\DataLayer\Mapper\CustomerDataMapper;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class AddDataToCustomerSection
{
    private CustomerSession $customerSession;
    private GroupRepositoryInterface $groupRepository;
    private CustomerSessionDataProviderInterface $customerSessionDataProvider;
    private CustomerDataMapper $customerDataMapper;
    private CustomerRepositoryInterface $customerRepository;
    private CollectionFactory $orderCollectionFactory;

    /**
     * Customer constructor.
     * @param CustomerSession $customerSession
     * @param GroupRepositoryInterface $groupRepository
     * @param CustomerSessionDataProviderInterface $customerSessionDataProvider
     * @param CustomerDataMapper $customerDataMapper
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerSession $customerSession,
        GroupRepositoryInterface $groupRepository,
        CustomerSessionDataProviderInterface $customerSessionDataProvider,
        CustomerDataMapper $customerDataMapper,
        CustomerRepositoryInterface $customerRepository,
        CollectionFactory $orderCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        $this->customerSessionDataProvider = $customerSessionDataProvider;
        $this->customerDataMapper = $customerDataMapper;
        $this->customerRepository = $customerRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @param CustomerData $subject
     * @param mixed $result
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function afterGetSectionData(CustomerData $subject, $result)
    {
        if (!is_array($result)) {
            return $result;
        }


        $gtmData = $this->getGtmData();
        $gtmOnce = $this->customerSessionDataProvider->get();
        $this->customerSessionDataProvider->clear();

        return array_merge($result, ['gtm' => $gtmData, 'gtm_events' => $gtmOnce]);
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getGtmData(): array
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [
                'customerLoggedIn' => 0,
                'customerId' => 0,
                'customerGroupId' => 0,
                'customerGroupCode' => 'GUEST',
                'visitorLoginState' => 'Logged out',
                'visitorLifeTimeValue' => 0,
                'visitorExistingCustomer' => 'No',
                'visitorType' => 'NOT LOGGED IN'
            ];
        }

        $customerId = $this->customerSession->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);
        $customerGtmData = $this->customerDataMapper->mapByCustomer($customer);
        $customerGroup = $this->groupRepository->getById($this->customerSession->getCustomerGroupId());

        return array_merge([
            'customerLoggedIn' => 1,
            'customerId' => $customerId,
            'customerGroupId' => $customerGroup->getId(),
            'customerGroupCode' => strtoupper($customerGroup->getCode()),
            'visitorLoginState' => 'logged in',
            'visitorLifeTimeValue' => $this->getLifeTimeValue($customer->getEmail()),
            'visitorExistingCustomer' => $this->getLifeTimeValue($customer->getEmail()) > 0 ? 'Yes' : 'No',
            'visitorType' => 'LOGGED IN'
        ], $customerGtmData);
    }

    private function getLifeTimeValue($customerEmail) 
    {
        $total=0;
        $orders = $this->orderCollectionFactory->create()
            ->addAttributeToFilter('customer_email', $customerEmail);
        foreach ($orders as $order) {
            $total=$total+$order->getGrandTotal();
        }

        return $total;
    }
}