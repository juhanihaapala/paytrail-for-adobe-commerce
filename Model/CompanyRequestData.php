<?php

namespace Paytrail\PaymentService\Model;

use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;

/**
 * Class CompanyRequestData
 */
class CompanyRequestData
{
    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;

    /**
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        CompanyRepositoryInterface $companyRepository
    )
    {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->companyRepository = $companyRepository;
    }

    public function setCompanyRequestData($customer, $billingAddress)
    {
        if ($billingAddress->getCompany()) {
            $customer->setCompanyName($billingAddress->getCompany());

            return $customer;
        }
        if ($this->customerSession->isLoggedIn()) {
            $companyId = $this->customerRepository->get($customer->getEmail())->getExtensionAttributes()->getCompanyAttributes()->getCompanyId() ?? null;
            if ($companyId) {
                $customer->setCompanyName($this->companyRepository->get($companyId)->getCompanyName());

                return $customer;
            }

            return $customer;
        }

        return $customer;
    }
}
