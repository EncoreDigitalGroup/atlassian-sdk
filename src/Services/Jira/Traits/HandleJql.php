<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Traits;

use EncoreDigitalGroup\Atlassian\Helpers\AuthHelper;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueSearchQueryResult;
use PHPGenesis\Http\HttpClient;

trait HandleJql
{
    public const string ISSUE_SEARCH_ENDPOINT = '/rest/api/2/search';

    public function jql(string $query, int $startAt = 0, int $maxResults = 50): IssueSearchQueryResult
    {
        AuthHelper::validate($this);

        $response = HttpClient::withBasicAuth($this->username, $this->token)
            ->get($this->hostname . self::ISSUE_SEARCH_ENDPOINT, [
                'jql' => $query,
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
}