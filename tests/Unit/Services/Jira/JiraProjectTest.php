<?php

use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueSearchQueryResult;
use Illuminate\Support\Facades\Http;

test('getIssues returns the correct instance of IssueSearchQueryResult with valid data', function () {
    Http::fake([
        'https://example.atlassian.net/rest/api/2/search*' => Http::response([
            "expand" => "schema,names",
            "startAt" => 0,
            "maxResults" => 50,
            "total" => 2,
            "issues" => [
                [
                    "expand" => "",
                    "id" => "10001",
                    "self" => "https://example.atlassian.net/rest/api/2/issue/10001",
                    "key" => "TEST-1",
                    "fields" => [
                        "summary" => "Test Issue 1",
                        "description" => "This is a test issue",
                        "status" => [
                            "self" => "https://example.atlassian.net/rest/api/2/status/10001",
                            "description" => "Issue is open and ready for the assignee to start work on it.",
                            "iconUrl" => "https://example.atlassian.net/",
                            "name" => "Open",
                            "id" => "10001",
                        ],
                        "priority" => [
                            "self" => "https://example.atlassian.net/rest/api/2/priority/3",
                            "iconUrl" => "https://example.atlassian.net/images/icons/priorities/medium.svg",
                            "name" => "Medium",
                            "id" => "3",
                        ],
                        "issuetype" => [
                            "self" => "https://example.atlassian.net/rest/api/2/issuetype/10001",
                            "id" => "10001",
                            "description" => "",
                            "iconUrl" => "https://example.atlassian.net/rest/api/2/universal_avatar/view/type/issuetype/avatar/10001?size=medium",
                            "name" => "Technical Debt",
                            "subtask" => false,
                            "avatarId" => 10001,
                            "hierarchyLevel" => 0,
                        ],
                        "project" => [
                            "self" => "https://example.atlassian.net/rest/api/2/project/1001",
                            "id" => "10001",
                            "key" => "TEST",
                            "name" => "TEST PROJECT",
                            "projectTypeKey" => "software",
                            "simplified" => false,
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    // Call the method under test
    $result = JiraProject::getIssues('TEST');

    // Assertions
    expect($result)->toBeInstanceOf(IssueSearchQueryResult::class)
        ->and($result->total)->toEqual(2)
        ->and($result->issues)->toBeArray()
        ->and($result->issues)->toHaveCount(1)
        ->and($result->issues[0])->toHaveProperties(['id', 'key', 'fields'])
        ->and($result->issues[0]->key)->toEqual('TEST-1');
    // Add more assertions as needed
});