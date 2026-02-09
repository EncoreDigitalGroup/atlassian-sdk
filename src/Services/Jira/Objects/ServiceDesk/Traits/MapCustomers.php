<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Customer;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\PagedCustomerList;

/**
 * Trait for mapping customer-related API responses to domain objects
 *
 * @internal
 */
trait MapCustomers
{
    /**
     * Maps API response data to a Customer object
     *
     * @param  mixed  $data  The API response data
     * @return Customer The mapped customer object
     */
    private function mapCustomer(mixed $data): Customer
    {
        return new Customer(
            accountId: $data['accountId'] ?? null,
            name: $data['name'] ?? null,
            key: $data['key'] ?? null,
            displayName: $data['displayName'] ?? null,
            emailAddress: $data['emailAddress'] ?? null,
            active: $data['active'] ?? null,
            timeZone: $data['timeZone'] ?? null,
            links: $data['_links'] ?? null,
        );
    }

    /**
     * Maps API response data to a PagedCustomerList object
     *
     * @param  mixed  $data  The API response data
     * @return PagedCustomerList The mapped paged customer list
     */
    private function mapPagedCustomerList(mixed $data): PagedCustomerList
    {
        $customers = [];
        if (isset($data['values']) && is_array($data['values'])) {
            foreach ($data['values'] as $customerData) {
                $customers[] = $this->mapCustomer($customerData);
            }
        }

        return new PagedCustomerList(
            expands: $data['_expands'] ?? null,
            size: $data['size'] ?? null,
            start: $data['start'] ?? null,
            limit: $data['limit'] ?? null,
            isLastPage: $data['isLastPage'] ?? null,
            links: $data['_links'] ?? null,
            values: $customers,
        );
    }
}
