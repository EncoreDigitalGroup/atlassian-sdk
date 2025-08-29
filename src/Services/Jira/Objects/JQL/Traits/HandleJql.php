<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\JqlResult;

trait HandleJql
{
    use InteractsWithAtlassian;

    public const string ISSUE_SEARCH_ENDPOINT = '/rest/api/2/search';

    public function jql(string $query, int $startAt = 0, int $maxResults = 50): JqlResult
    {
        $response = $this->client()->get($this->hostname . self::ISSUE_SEARCH_ENDPOINT, [
            'jql' => $query,
            'startAt' => $startAt,
            'maxResults' => $maxResults,
        ]);

        $response = json_decode($response->body());

        $jqlResult = new JqlResult;
        $jqlResult->expand = $response->expand;
        $jqlResult->startAt = $response->startAt;
        $jqlResult->maxResults = $response->maxResults;
        $jqlResult->total = $response->total;
        $jqlResult->issues = [];

        foreach ($response->issues as $issue) {
            $jqlResult->issues[] = $this->mapIssues($issue);
        }

        return $jqlResult;
    }
}