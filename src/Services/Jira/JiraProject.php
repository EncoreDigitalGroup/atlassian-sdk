<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\AtlassianHelper;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueSearchQueryResult;
use EncoreDigitalGroup\Atlassian\Services\Jira\Traits\MapIssues;
use EncoreDigitalGroup\StdLib\Exceptions\NullExceptions\ClassPropertyNullException;
use Illuminate\Support\Facades\Http;

/**
 * @experimental
 *
 * @api
 */
class JiraProject
{
    use MapIssues;

    public function __construct(
        public ?string $hostname = null,
        public ?string $username = null,
        public ?string $token = null,
    ) {
        $this->hostname = $hostname ?? AtlassianHelper::getHostname();
        $this->username = $username ?? AtlassianHelper::getUsername();
        $this->token = $token ?? AtlassianHelper::getToken();
    }

    public static function make(?string $hostname = null, ?string $username = null, ?string $token = null): JiraProject
    {
        return new self($hostname, $username, $token);
    }

    public function getIssues(string $projectKey, int $startAt = 0, int $maxResults = 50): IssueSearchQueryResult
    {
        if (is_null($this->username)) {
            throw new ClassPropertyNullException('username');
        }

        if (is_null($this->token)) {
            throw new ClassPropertyNullException('token');
        }

        $response = Http::withBasicAuth($this->username, $this->token)
            ->get($this->hostname . '/rest/api/2/search', [
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
            $issueSearchQueryResult->issues[] = self::mapIssues($issue);
        }

        return $issueSearchQueryResult;
    }
}
