# JiraServiceDesk

The `JiraServiceDesk` class provides an interface to interact with Jira Service Desk customer requests via the Atlassian Service Desk REST API. This class is part of the
`EncoreDigitalGroup\Atlassian\Services\Jira` namespace and enables creating and retrieving Service Desk customer requests.

The Service Desk API uses different endpoints (`/rest/servicedeskapi/*`) compared to the standard Jira API (`/rest/api/2/*`), but authentication follows the same pattern
using basic auth with username/email and API token.

## Available Methods

Below are the methods available in the `JiraServiceDesk` class:

### Request Management

- `createRequest(ServiceDeskRequest $request): ServiceDeskRequest`
- `getRequest(string $issueIdOrKey): ServiceDeskRequest`

### Customer Management

- `customers(): ServiceDeskCustomers` - Returns a sub-resource for customer operations

The `ServiceDeskCustomers` sub-resource provides:

- `create(string $displayName, string $email, bool $strictConflictStatusCode = false): Customer`
- `list(string $serviceDeskId, ?string $query = null, int $start = 0, int $limit = 50): PagedCustomerList`
- `add(string $serviceDeskId, array $accountIds): void`
- `remove(string $serviceDeskId, array $accountIds): void`
- `revokePortalAccess(string $accountId): void`

## Method Details

### `createRequest`

Create a new Service Desk customer request in a Jira Service Desk project.

#### Parameters

- `ServiceDeskRequest $request`: An instance of the `ServiceDeskRequest` class containing:
    - `serviceDeskId` *(required)*: The ID of the Service Desk (found in Service Desk settings)
    - `requestTypeId` *(required)*: The ID of the request type (found in request type configuration)
    - `requestFieldValues` *(required)*: Field values including at minimum the fields required by the request type (typically 'summary' and 'description')
    - `raiseOnBehalfOf` *(optional)*: Account ID of the user to create the request for (requires permission)

#### Return Value

Returns an instance of `ServiceDeskRequest` containing the created request with all server-populated fields including:

- `issueId`: The numeric ID of the created issue
- `issueKey`: The issue key (e.g., 'SD-123')
- `createdDate`: The date/time the request was created
- `reporter`: Information about the user who created the request
- `sla`: Array of SLA information for the request

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequest;

// Create service instance
$service = JiraServiceDesk::make();

// Create a new request object
$request = new ServiceDeskRequest();
$request->serviceDeskId = '10';
$request->requestTypeId = '25';

// Set required field values
$request->requestFieldValues->setField('summary', 'Need help with login');
$request->requestFieldValues->setField('description', 'I cannot access my account');

// Set custom field values if needed
$request->requestFieldValues->setField('customfield_10001', 'High priority');

// Optional: raise request on behalf of another user
$request->raiseOnBehalfOf = '5b10a2844c20165700ede21g';

// Create the request
$created = $service->createRequest($request);

echo "Created request: {$created->issueKey}" . PHP_EOL;
echo "Reporter: {$created->reporter->displayName}" . PHP_EOL;
echo "Created: {$created->createdDate}" . PHP_EOL;
```

### `getRequest`

Retrieve an existing Service Desk request by issue ID or key.

#### Parameters

- `string $issueIdOrKey`: The issue ID (numeric, e.g., '10001') or issue key (e.g., 'SD-123')

#### Return Value

Returns an instance of `ServiceDeskRequest` containing all request data including field values, reporter information, SLA details, and metadata.

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Retrieve by issue key
$request = $service->getRequest('SD-123');

// Retrieve by issue ID
$request = $service->getRequest('10001');

// Access request data
echo "Summary: " . $request->requestFieldValues->getField('summary') . PHP_EOL;
echo "Description: " . $request->requestFieldValues->getField('description') . PHP_EOL;
echo "Reporter: {$request->reporter->displayName}" . PHP_EOL;
echo "Email: {$request->reporter->emailAddress}" . PHP_EOL;

// Access custom fields
$customValue = $request->requestFieldValues->getField('customfield_10001');
echo "Custom field: {$customValue}" . PHP_EOL;

// Check SLA information
foreach ($request->sla as $sla) {
    echo "SLA: {$sla->name} - Completed: " . ($sla->completedCycle ? 'Yes' : 'No') . PHP_EOL;
}
```

## Customer Management

The `JiraServiceDesk` class provides comprehensive customer management capabilities through the `customers()` sub-resource. This allows you to create customers, list
them, add/remove them from service desks, and manage their access permissions.

### `customers`

Access the customer management sub-resource.

#### Return Value

Returns an instance of `ServiceDeskCustomers` that provides all customer-related operations.

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Access the customer management sub-resource
$customers = $service->customers();
```

### Creating Customers

Create a new customer globally in your Jira instance.

#### Method Signature

```php
create(string $displayName, string $email, bool $strictConflictStatusCode = false): Customer
```

#### Parameters

- `string $displayName` *(required)*: The display name for the customer
- `string $email` *(required)*: The email address for the customer
- `bool $strictConflictStatusCode` *(optional)*: If true, returns 409 status code on conflict instead of 200 (default: false)

#### Permissions Required

- Jira Administrator global permission

#### Return Value

Returns a `Customer` object containing:

- `accountId`: The Atlassian account ID
- `name`: The username
- `key`: The customer key (deprecated by Atlassian)
- `displayName`: The display name
- `emailAddress`: The email address
- `active`: Whether the account is active
- `timeZone`: The user's timezone
- `links`: HAL links for the customer

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Create a new customer
$customer = $service->customers()->create(
    displayName: 'John Doe',
    email: 'john.doe@example.com'
);

echo "Created customer: {$customer->displayName}" . PHP_EOL;
echo "Account ID: {$customer->accountId}" . PHP_EOL;
echo "Email: {$customer->emailAddress}" . PHP_EOL;
echo "Active: " . ($customer->active ? 'Yes' : 'No') . PHP_EOL;

// Create with strict conflict status code
$customer = $service->customers()->create(
    displayName: 'Jane Smith',
    email: 'jane.smith@example.com',
    strictConflictStatusCode: true
);
```

### Listing Customers

Retrieve a paginated list of customers for a specific service desk with optional filtering.

#### Method Signature

```php
list(string $serviceDeskId, ?string $query = null, int $start = 0, int $limit = 50): PagedCustomerList
```

#### Parameters

- `string $serviceDeskId` *(required)*: The ID of the service desk
- `string $query` *(optional)*: Filter customers by name or email (default: null)
- `int $start` *(optional)*: The starting index for pagination (default: 0)
- `int $limit` *(optional)*: The maximum number of results per page (default: 50)

#### Return Value

Returns a `PagedCustomerList` object containing:

- `expands`: The expands applied to the customer list
- `size`: The number of items in this page
- `start`: The starting index of the page
- `limit`: The maximum number of items per page
- `isLastPage`: Whether this is the last page
- `links`: HAL links for pagination (base, context, next, prev)
- `values`: Array of `Customer` objects

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// List all customers for a service desk
$customers = $service->customers()->list('10');

echo "Found {$customers->size} customers" . PHP_EOL;
echo "Is last page: " . ($customers->isLastPage ? 'Yes' : 'No') . PHP_EOL;

foreach ($customers->values as $customer) {
    echo "- {$customer->displayName} ({$customer->emailAddress})" . PHP_EOL;
}

// Filter customers by query
$filtered = $service->customers()->list(
    serviceDeskId: '10',
    query: 'john'
);

// Paginate through results
$page1 = $service->customers()->list(
    serviceDeskId: '10',
    start: 0,
    limit: 25
);

$page2 = $service->customers()->list(
    serviceDeskId: '10',
    start: 25,
    limit: 25
);
```

### Adding Customers to Service Desk

Add existing customers to a service desk, granting them access to submit requests.

#### Method Signature

```php
add(string $serviceDeskId, array $accountIds): void
```

#### Parameters

- `string $serviceDeskId` *(required)*: The ID of the service desk
- `array $accountIds` *(required)*: Array of customer account IDs to add

#### Notes

- Duplicate account IDs are automatically removed
- The method returns void; no response is returned on success
- If a customer is already added to the service desk, they are ignored

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Add single customer to service desk
$service->customers()->add('10', ['5b10a2844c20165700ede21g']);

// Add multiple customers
$service->customers()->add('10', [
    '5b10a2844c20165700ede21g',
    '5b10a2844c20165700ede22h',
    '5b10a2844c20165700ede23i',
]);

// Duplicates are automatically removed
$service->customers()->add('10', [
    '5b10a2844c20165700ede21g',
    '5b10a2844c20165700ede21g', // Duplicate, will be ignored
    '5b10a2844c20165700ede22h',
]);
```

### Removing Customers from Service Desk

Remove customers from a service desk, revoking their access to submit requests.

#### Method Signature

```php
remove(string $serviceDeskId, array $accountIds): void
```

#### Parameters

- `string $serviceDeskId` *(required)*: The ID of the service desk
- `array $accountIds` *(required)*: Array of customer account IDs to remove

#### Requirements

- The service desk must have **closed access** enabled
- You must have appropriate permissions to modify service desk customers

#### Notes

- Duplicate account IDs are automatically removed
- The method returns void; no response is returned on success

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Remove single customer from service desk
$service->customers()->remove('10', ['5b10a2844c20165700ede21g']);

// Remove multiple customers
$service->customers()->remove('10', [
    '5b10a2844c20165700ede21g',
    '5b10a2844c20165700ede22h',
]);
```

### Revoking Portal Access

Revoke portal-only access permission for a customer account.

#### Method Signature

```php
revokePortalAccess(string $accountId): void
```

#### Parameters

- `string $accountId` *(required)*: The account ID of the customer

#### Notes

- This removes the portal-only access permission from a customer
- The method returns void; no response is returned on success

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// Revoke portal access for a customer
$service->customers()->revokePortalAccess('5b10a2844c20165700ede21g');
```

### Complete Customer Management Example

Here's a complete example demonstrating all customer management operations:

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;

$service = JiraServiceDesk::make();

// 1. Create a new customer
$customer = $service->customers()->create(
    displayName: 'John Doe',
    email: 'john.doe@example.com'
);
echo "Created customer: {$customer->accountId}" . PHP_EOL;

// 2. Add customer to service desk
$service->customers()->add('10', [$customer->accountId]);
echo "Added customer to service desk" . PHP_EOL;

// 3. List all customers
$customers = $service->customers()->list('10');
echo "Total customers: {$customers->size}" . PHP_EOL;

// 4. Search for specific customers
$filtered = $service->customers()->list(
    serviceDeskId: '10',
    query: 'john'
);
foreach ($filtered->values as $customer) {
    echo "Found: {$customer->displayName}" . PHP_EOL;
}

// 5. Remove customer from service desk
$service->customers()->remove('10', [$customer->accountId]);
echo "Removed customer from service desk" . PHP_EOL;

// 6. Revoke portal access
$service->customers()->revokePortalAccess($customer->accountId);
echo "Revoked portal access" . PHP_EOL;
```

## Working with Field Values

The `ServiceDeskRequestFieldValues` object provides a flexible way to work with both standard and custom fields.

### Setting Field Values (for Request Creation)

When creating a request, field values are sent as a simple object/map:

```php
$request = new ServiceDeskRequest();

// Standard fields
$request->requestFieldValues->setField('summary', 'Request title');
$request->requestFieldValues->setField('description', 'Request description');

// Custom fields
$request->requestFieldValues->setField('customfield_10001', 'Value 1');
$request->requestFieldValues->setField('customfield_10002', 'Value 2');
```

### Getting Field Values (from Response)

When retrieving a request, the API returns field values as an array of objects with `fieldId`, `label`, and `value` properties. The SDK automatically converts this to a
simple key-value format:

```php
$summary = $request->requestFieldValues->getField('summary');
$customValue = $request->requestFieldValues->getField('customfield_10001');

// Returns null if field doesn't exist
$nonExistent = $request->requestFieldValues->getField('nonexistent'); // null
```

**Note:** The API response format differs from the request format:

- **Request payload:** `{"summary": "value", "description": "value"}` (object/map)
- **Response payload:** `[{"fieldId": "summary", "label": "...", "value": "..."}, ...]` (array of objects)

The SDK handles this conversion automatically.

## Finding Service Desk and Request Type IDs

To use the `createRequest` method, you need to find the Service Desk ID and Request Type ID:

### Service Desk ID

1. Navigate to your Jira Service Desk project
2. Go to Project Settings > Service Desk settings
3. The Service Desk ID is displayed in the settings or can be found in the URL

### Request Type ID

1. In your Service Desk project, go to Project Settings > Request types
2. Click on a request type to view its details
3. The Request Type ID can be found in the URL or request type configuration

Alternatively, you can use the Atlassian API to programmatically retrieve these IDs:

- Service Desks: `GET /rest/servicedeskapi/servicedesk`
- Request Types: `GET /rest/servicedeskapi/servicedesk/{serviceDeskId}/requesttype`

## Handling Responses

### ServiceDeskRequest Object

The `ServiceDeskRequest` object returned by both methods contains the following properties:

- `issueId`: The numeric ID of the issue
- `issueKey`: The issue key (e.g., 'SD-123')
- `serviceDeskId`: The ID of the Service Desk
- `requestTypeId`: The ID of the request type
- `requestFieldValues`: A `ServiceDeskRequestFieldValues` object containing all field values
- `reporter`: A `ServiceDeskRequestParticipant` object with user information
- `raiseOnBehalfOf`: Account ID if the request was raised on behalf of another user
- `createdDate`: ISO 8601 formatted date string
- `sla`: Array of `ServiceDeskRequestSla` objects

### ServiceDeskRequestParticipant Object

The reporter information includes:

- `accountId`: The Atlassian account ID
- `name`: The username
- `displayName`: The display name
- `emailAddress`: The email address
- `active`: Whether the user is active
- `timeZone`: The user's timezone

### ServiceDeskRequestSla Object

SLA information includes:

- `id`: The SLA ID
- `name`: The SLA name (e.g., 'Time to resolution')
- `completedCycle`: Whether the SLA cycle is completed
- `remainingTime`: Array with time information (millis, friendly format)

## Configuration

### Required Environment Variables

The `JiraServiceDesk` class requires the following configuration:

```env
ATLASSIAN_HOSTNAME=https://your-domain.atlassian.net
ATLASSIAN_USERNAME=your-email@example.com
ATLASSIAN_TOKEN=your-api-token
```

### Generating an API Token

1. Visit https://id.atlassian.com/manage-profile/security/api-tokens
2. Click "Create API token"
3. Give it a descriptive name
4. Copy the token and add it to your `.env` file

## Permissions

### Required Permissions

To use the Service Desk API, you need:

- **Browse project** permission for the Service Desk project
- **Create issues** permission to create requests
- **Service Desk Agent** role to raise requests on behalf of others

### Raising Requests on Behalf of Others

To use the `raiseOnBehalfOf` parameter:

1. You must have the Service Desk Agent role
2. The user you're creating the request for must have permission to access the Service Desk
3. Provide the Atlassian account ID (not username or email) in the `raiseOnBehalfOf` field

## Notes

- This class is marked as `@experimental` and `@api`, indicating that it is part of the public API but should be used with caution as it may be subject to changes.
- Authentication is handled via basic auth using a username and API token, which are retrieved using the `AtlassianHelper` class.
- Field validation is performed by the Jira Service Desk API. If required fields are missing or invalid values are provided, the API will return an error.
- Custom field IDs (e.g., `customfield_10001`) are specific to your Jira instance and can be found in the Jira field configuration.

## API Reference

For more information about the Atlassian Service Desk REST API, see:

### Request Management

- [Service Desk REST API Documentation](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/)
- [Create Customer Request](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/#api-rest-servicedeskapi-request-post)
- [Get Customer Request](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/#api-rest-servicedeskapi-request-issueidorkey-get)

### Customer Management

- [Customer API Documentation](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/)
- [Create Customer](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/#api-rest-servicedeskapi-customer-post)
- [List Customers](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/#api-rest-servicedeskapi-servicedesk-servicedeskid-customer-get)
- [Add Customers to Service Desk](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/#api-rest-servicedeskapi-servicedesk-servicedeskid-customer-post)
- [Remove Customers from Service Desk](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/#api-rest-servicedeskapi-servicedesk-servicedeskid-customer-delete)
- [Revoke Portal Access](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-customer/#api-rest-servicedeskapi-customer-user-accountid-revoke-portal-only-access-put)
