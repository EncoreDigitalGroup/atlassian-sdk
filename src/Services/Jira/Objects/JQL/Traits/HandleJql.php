<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL\JqlResult;

trait HandleJql
{
    use InteractsWithAtlassian;

    public const string ISSUE_SEARCH_ENDPOINT = '/rest/api/2/search';

    public function jql(string $query, ?string $nextPageToken = null, int $maxResults = 50): JqlResult
    {
        $queryParams = [
            "jql" => $query,
            "maxResults" => $maxResults,
            "fields" => "*all",
        ];

        if (!is_null($nextPageToken)) {
            $queryParams["nextPageToken"] = $nextPageToken;
        }

        $response = $this->client()->get($this->hostname . self::ISSUE_SEARCH_ENDPOINT, $queryParams);

        $response = json_decode($response->body());

        $jqlResult = new JqlResult;
        $jqlResult->expand = $response->expand;
        $jqlResult->nextPageToken = $response->nextPageToken;
        $jqlResult->maxResults = $response->maxResults;
        $jqlResult->total = $response->total;
        $jqlResult->issues = [];

        foreach ($response->issues as $issue) {
            $jqlResult->issues[] = $this->mapIssues($issue);
        }

        return $jqlResult;
    }
}