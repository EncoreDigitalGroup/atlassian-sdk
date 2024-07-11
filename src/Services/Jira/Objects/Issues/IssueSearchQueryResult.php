<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues;

class IssueSearchQueryResult
{
    public ?string $expand;
    public int $startAt = 0;
    public int $maxResults = 50;
    public int $total;

    /** @var array<Issue> */
    public array $issues;
}
