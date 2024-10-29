<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueCustomField;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Traits\MapIssues;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\JqlResult;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\Traits\HandleJql;
use Illuminate\Support\Collection;

/**
 * @experimental
 *
 * @api
 */
class JiraProject
{
    use HandleJql;
    use InteractsWithAtlassian;
    use MapIssues;

    public const string ISSUE_ENDPOINT = '/rest/api/2/issue';

    public function getIssues(string $projectKey, int $startAt = 0, int $maxResults = 50): JqlResult
    {
        return $this->jql("project={$projectKey}", $startAt, $maxResults);
    }

    public function createIssue(Issue $issue): Issue
    {
        /** @var string $issueJson */
        $issueJson = json_encode($issue);

        /** @var array $issueArray */
        $issueArray = json_decode($issueJson, true);

        $customFields = new Collection($issueArray['fields']['customFields']);

        unset($issueArray['fields']['customFields']);

        $customFields->each(function($customField) use (&$issueArray) {
            $issueArray['fields'][$customField['name']] = $customField['value'];
        });

        $response = $this->client()->post($this->hostname . self::ISSUE_ENDPOINT, $issueArray);

        $response = json_decode($response->body());

        return $this->getIssue($response->id);
    }

    public function getIssue(string $id): Issue
    {
        $response = $this->client()->get($this->hostname . self::ISSUE_ENDPOINT . '/' . $id);

        $response = json_decode($response->body());

        return $this->mapIssues($response);
    }
}
