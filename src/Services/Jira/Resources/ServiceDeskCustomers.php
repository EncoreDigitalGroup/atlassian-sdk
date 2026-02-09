<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Resources;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Customer;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\PagedCustomerList;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Traits\MapCustomers;
use Illuminate\Http\Client\PendingRequest;

/**
 * Service Desk Customers sub-resource
 *
 * Provides methods for managing Service Desk customers including creating,
 * listing, adding to service desks, removing, and revoking access.
 *
 * @api
 * @experimental
 */
class ServiceDeskCustomers
{
    use MapCustomers;

    /**
     * @param PendingRequest $client The authenticated HTTP client
     * @param string $hostname The Atlassian instance hostname
     */
    public function __construct(
        private PendingRequest $client,
        private string         $hostname,
    ) {}

    /**
     * Create a new customer globally
     *
     * Requires Jira Administrator global permission.
     *
     * @param string $displayName The display name for the customer
     * @param string $email The email address for the customer
     * @param bool $strictConflictStatusCode Whether to return 409 on conflict instead of 200
     * @return Customer The created customer
     *
     * @api
     * @experimental
     */
    public function create(string $displayName, string $email, bool $strictConflictStatusCode = false): Customer
    {
        $url = "{$this->hostname}/rest/servicedeskapi/customer";

        $query = [];
        if ($strictConflictStatusCode) {
            $query['strictConflictStatusCode'] = 'true';
        }

        $response = $this->client
            ->post($url, [
                    'displayName' => $displayName,
                    'email' => $email,
                ] + (!empty($query) ? $query : []));

        return $this->mapCustomer($response->json());
    }

    /**
     * List customers for a service desk
     *
     * Returns a list of customers for a service desk with optional filtering by name or email.
     *
     * @param string $serviceDeskId The ID of the service desk
     * @param string|null $query Filter customers by name or email (optional)
     * @param int $start The starting index (default: 0)
     * @param int $limit The maximum number of results (default: 50)
     * @return PagedCustomerList The paginated list of customers
     *
     * @api
     * @experimental
     */
    public function list(string $serviceDeskId, ?string $query = null, int $start = 0, int $limit = 50): PagedCustomerList
    {
        $url = "{$this->hostname}/rest/servicedeskapi/servicedesk/{$serviceDeskId}/customer";

        $queryParams = [
            'start' => $start,
            'limit' => $limit,
        ];

        if ($query !== null) {
            $queryParams['query'] = $query;
        }

        $response = $this->client->get($url, $queryParams);

        return $this->mapPagedCustomerList($response->json());
    }

    /**
     * Add customers to a service desk
     *
     * Adds one or more existing customers to a service desk.
     * Duplicate account IDs are automatically removed.
     *
     * @param string $serviceDeskId The ID of the service desk
     * @param array<string> $accountIds Array of customer account IDs to add
     * @return void
     *
     * @api
     * @experimental
     */
    public function add(string $serviceDeskId, array $accountIds): void
    {
        $url = "{$this->hostname}/rest/servicedeskapi/servicedesk/{$serviceDeskId}/customer";

        $this->client->post($url, [
            'accountIds' => array_values(array_unique($accountIds)),
        ]);
    }

    /**
     * Remove customers from a service desk
     *
     * Removes one or more customers from a service desk.
     * The service desk must have closed access enabled.
     * Duplicate account IDs are automatically removed.
     *
     * @param string $serviceDeskId The ID of the service desk
     * @param array<string> $accountIds Array of customer account IDs to remove
     * @return void
     *
     * @api
     * @experimental
     */
    public function remove(string $serviceDeskId, array $accountIds): void
    {
        $url = "{$this->hostname}/rest/servicedeskapi/servicedesk/{$serviceDeskId}/customer";

        $this->client->delete($url, [
            'accountIds' => array_values(array_unique($accountIds)),
        ]);
    }

    /**
     * Revoke portal-only access permission for a customer
     *
     * Removes the portal-only access permission for a customer account.
     *
     * @param string $accountId The account ID of the customer
     * @return void
     *
     * @api
     * @experimental
     */
    public function revokePortalAccess(string $accountId): void
    {
        $url = "{$this->hostname}/rest/servicedeskapi/customer/user/{$accountId}/revoke-portal-only-access";

        $this->client->put($url, []);
    }
}
