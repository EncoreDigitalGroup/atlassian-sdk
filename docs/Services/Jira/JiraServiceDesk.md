# JiraServiceDesk

The `JiraServiceDesk` class provides an interface to interact with Jira Service Desk customer requests via the Atlassian Service Desk REST API. This class is part of the
`EncoreDigitalGroup\Atlassian\Services\Jira` namespace and enables creating and retrieving Service Desk customer requests.

The Service Desk API uses different endpoints (`/rest/servicedeskapi/*`) compared to the standard Jira API (`/rest/api/2/*`), but authentication follows the same pattern
using basic auth with username/email and API token.

## Available Methods

Below are the methods available in the `JiraServiceDesk` class:

- `createRequest(ServiceDeskRequest $request): ServiceDeskRequest`
- `getRequest(string $issueIdOrKey): ServiceDeskRequest`

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

## Working with Field Values

The `ServiceDeskRequestFieldValues` object provides a flexible way to work with both standard and custom fields.

### Setting Field Values

```php
$request = new ServiceDeskRequest();

// Standard fields
$request->requestFieldValues->setField('summary', 'Request title');
$request->requestFieldValues->setField('description', 'Request description');

// Custom fields
$request->requestFieldValues->setField('customfield_10001', 'Value 1');
$request->requestFieldValues->setField('customfield_10002', 'Value 2');
```

### Getting Field Values

```php
$summary = $request->requestFieldValues->getField('summary');
$customValue = $request->requestFieldValues->getField('customfield_10001');

// Returns null if field doesn't exist
$nonExistent = $request->requestFieldValues->getField('nonexistent'); // null
```

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
- The Service Desk API may return additional fields not mapped in the current implementation. These can be added incrementally as needed.
- Field validation is performed by the Jira Service Desk API. If required fields are missing or invalid values are provided, the API will return an error.
- Custom field IDs (e.g., `customfield_10001`) are specific to your Jira instance and can be found in the Jira field configuration.

## API Reference

For more information about the Atlassian Service Desk REST API, see:

- [Service Desk REST API Documentation](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/)
- [Create Customer Request](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/#api-rest-servicedeskapi-request-post)
- [Get Customer Request](https://developer.atlassian.com/cloud/jira/service-desk/rest/api-group-request/#api-rest-servicedeskapi-request-issueidorkey-get)
