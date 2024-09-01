<?php

use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueFields;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueSearchQueryResult;
use Illuminate\Support\Facades\Http;

test('make returns instance of JiraProject', function () {
    $jiraProject = JiraProject::make();

    expect($jiraProject)->toBeInstanceOf(JiraProject::class);
});

test('getIssues returns the correct instance of IssueSearchQueryResult with valid data', function () {
    // Call the method under test
    $result = JiraProject::make()->getIssues('TEST');

    // Assertions
    expect($result)->toBeInstanceOf(IssueSearchQueryResult::class)
        ->and($result->total)->toEqual(2)
        ->and($result->issues)->toBeArray()
        ->and($result->issues)->toHaveCount(1)
        ->and($result->issues[0])->toHaveProperties(['id', 'key', 'fields'])
        ->and($result->issues[0]->key)->toEqual('TEST-1');
});

test('createIssue returns the correct instance of Issue with valid data', function () {
    // Call the method under test
    $issue = new Issue();
    $issue->fields = new IssueFields();
    $issue->fields->summary = 'Test Issue 1';
    $issue->fields->description = 'This is a test issue';

    $result = JiraProject::make()->createIssue($issue);

    // Assertions
    expect($result)->toBeInstanceOf(Issue::class)
        ->and($result->id)->toEqual('10001')
        ->and($result->fields)->toBeInstanceOf(IssueFields::class)
        ->and($result->fields->summary)->toEqual('Test Issue 1')
        ->and($result->fields->description)->toEqual('This is a test issue')
        ->and($result->key)->toEqual('TEST-1');
});