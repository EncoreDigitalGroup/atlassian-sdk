# JiraHelper

The `JiraHelper` class is part of the `EncoreDigitalGroup\Atlassian\Services\Jira` namespace, designed to assist with various JIRA-related operations.
This class provides utility methods to work with JIRA data and links.

## Methods

### `getKeyFromSmartLink`

Extracts the issue key from a JIRA smart link.

#### Parameters

- `string $smartLink`: The full URL of a JIRA issue.

#### Returns

- `?string`: The issue key extracted from the URL, or `null` if the pattern does not match.

#### Example

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraHelper;

$issueKey = JiraHelper::getKeyFromSmartLink('https://yourdomain.atlassian.net/browse/PROJECT-123');

echo $issueKey; // Outputs: PROJECT-123
```

## Notes

- The `getKeyFromSmartLink` method uses a regular expression to parse the issue key from the provided URL. It is designed to work with standard JIRA issue URLs.
- Ensure that the smart link provided is a valid JIRA issue URL to successfully extract the issue key.