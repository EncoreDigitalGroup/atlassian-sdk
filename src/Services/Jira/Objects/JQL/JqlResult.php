<?php

/*
 * Copyright (c) 2024-2025. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\JQL;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;

class JqlResult
{
    public ?string $expand;

    public ?string $nextPageToken = null;

    public int $maxResults = 50;

    public int $total;

    /** @var array<Issue> */
    public array $issues;
}
