# JiraProject

The `JiraProject` class provides an interface to interact with JIRA projects, specifically for retrieving issues within a project. This class is part of the
`EncoreDigitalGroup\Atlassian\Services\Jira` namespace and utilizes the Atlassian JIRA REST API to perform its operations.

## Available Methods

Below are the methods available in the `JiraProject` class:

- `getIssues(string $projectKey, int $startAt = 0, int $maxResults = 50): IssueSearchQueryResult`
- `createIssue(Issue $issue): Issue`
- `getIssue(string $id): Issue`

## Method Details

### `getIssues`

Retrieve a list of issues from a specific JIRA project.

#### Parameters

- `string $projectKey`: The key of the project from which issues are to be retrieved.
- `int $startAt` *(optional)*: The index of the first issue to return (0-based). Default is `0`.
- `int $maxResults` *(optional)*: The maximum number of issues to return. Default is `50`.

#### Return Value

Returns an instance of `IssueSearchQueryResult` containing the issues found, along with pagination details.

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;

$projectKey = "YOUR_PROJECT_KEY";
$issues = JiraProject::getIssues($projectKey);

foreach ($issues->issues as $issue) {
    echo $issue->key . PHP_EOL;
}
```

### `createIssue`

Create a new issue in JIRA.

#### Parameters

- `Issue $issue`: An instance of the `Issue` class representing the issue to be created.

#### Return Value

Returns an instance of `Issue` representing the created issue.

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueFields;

$jiraProject = JiraProject::make();
$newIssue = new Issue();
$newIssue->fields = new IssueFields();
$newIssue->fields->summary = "Your Issue Summary";
$newIssue->fields->description = "Your Issue Description";
$createdIssue = $jiraProject->createIssue($newIssue);

echo $createdIssue->key . PHP_EOL;
```

### `getIssue`

Retrieve a specific issue from JIRA by its ID.

#### Parameters

- `string $id`: The ID of the issue to be retrieved.

#### Return Value

Returns an instance of `Issue` representing the retrieved issue.

#### Example Usage

```php
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;

$jiraProject = JiraProject::make();
$issueId = "YOUR_ISSUE_ID";
$issue = $jiraProject->getIssue($issueId);

echo $issue->key . PHP_EOL;
```

## Handling Responses

The `IssueSearchQueryResult` object returned by `getIssues` contains the following properties:

- `expand`: Information about the fields that are included in the response.
- `startAt`: The starting index of the returned issues.
- `maxResults`: The maximum number of issues that were requested.
- `total`: The total number of issues that match the query.
- `issues`: An array of `Issue` objects representing the issues retrieved.

Each `Issue` object contains detailed information about an issue, including its status, priority, type, and more.

## Notes

- This class is marked as `@experimental` and `@api`, indicating that it is part of the public API but should be used with caution as it may be subject to changes.
- Authentication is handled via basic auth using a username and API token, which are retrieved using the `AtlassianHelper` class.