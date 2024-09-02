<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\AtlassianHelper;
use EncoreDigitalGroup\Atlassian\Helpers\AuthHelper;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueSearchQueryResult;
use EncoreDigitalGroup\Atlassian\Services\Jira\Traits\MapIssues;
use Illuminate\Support\Facades\Http;

/**
 * @experimental
 *
 * @api
 */
class JiraProject
{
    use MapIssues;

    public const string ISSUE_SEARCH_ENDPOINT = '/rest/api/2/search';
    public const string ISSUE_ENDPOINT = '/rest/api/2/issue';

    public function __construct(public string $hostname, public string $username, public string $token)
    {
        $this->hostname = $hostname ?: AtlassianHelper::getHostname();
        $this->username = $username ?: AtlassianHelper::getUsername();
        $this->token = $token ?: AtlassianHelper::getToken();
    }

    public static function make(?string $hostname = null, ?string $username = null, ?string $token = null): JiraProject
    {
        return new self(
            $hostname ?: AtlassianHelper::getHostname(),
            $username ?: AtlassianHelper::getUsername(),
            $token ?: AtlassianHelper::getToken()
        );
    }

    public function getIssues(string $projectKey, int $startAt = 0, int $maxResults = 50): IssueSearchQueryResult
    {
        AuthHelper::validate($this);

        $response = Http::withBasicAuth($this->username, $this->token)
            ->get($this->hostname . self::ISSUE_SEARCH_ENDPOINT, [
                'jql' => 'project=' . $projectKey,
                'startAt' => $startAt,
                'maxResults' => $maxResults,
            ]);

        $response = json_decode($response->body());

        $issueSearchQueryResult = new IssueSearchQueryResult();
        $issueSearchQueryResult->expand = $response->expand;
        $issueSearchQueryResult->startAt = $response->startAt;
        $issueSearchQueryResult->maxResults = $response->maxResults;
        $issueSearchQueryResult->total = $response->total;
        $issueSearchQueryResult->issues = [];

        foreach ($response->issues as $issue) {
            $issueSearchQueryResult->issues[] = $this->mapIssues($issue);
        }

        return $issueSearchQueryResult;
    }

    public function createIssue(Issue $issue): Issue
    {
        AuthHelper::validate($this);

        /** @var string $issueJson */
        $issueJson = json_encode($issue);

        /** @var array $issueArray */
        $issueArray = json_decode($issueJson, true);

        $response = Http::withBasicAuth($this->username, $this->token)
            ->post($this->hostname . self::ISSUE_ENDPOINT, $issueArray);

        $response = json_decode($response->body());

        return $this->getIssue($response->id);

    }

    public function getIssue(string $id): Issue
    {
        AuthHelper::validate($this);

        $response = Http::withBasicAuth($this->username, $this->token)
            ->get($this->hostname . self::ISSUE_ENDPOINT . '/' . $id);

        $response = json_decode($response->body());

        return $this->mapIssues($response);
    }
}
