<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Projects\Project;

class IssueFields
{
    public string $summary;
    public ?string $description;
    public IssueStatus $status;
    public IssuePriority $priority;
    public IssueType $type;
    public Project $project;

    public function __construct()
    {
        $this->status = new IssueStatus();
        $this->priority = new IssuePriority();
        $this->type = new IssueType();
        $this->project = new Project();
    }
}
