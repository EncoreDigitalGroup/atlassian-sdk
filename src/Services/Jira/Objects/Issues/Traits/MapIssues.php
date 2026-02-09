<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\Issue;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueFields;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssuePriority;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueStatus;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues\IssueType;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Projects\Project;

trait MapIssues
{
    private function mapIssues(mixed $data): Issue
    {
        $issue = new Issue;
        $issue->expand = $data->expand;
        $issue->id = $data->id;
        $issue->self = $data->self;
        $issue->key = $data->key;
        $issue->fields = new IssueFields;

        $issue->fields->status = new IssueStatus;
        $issue->fields->status->self = $data->fields->status->self;
        $issue->fields->status->description = $data->fields->status->description;
        $issue->fields->status->iconUrl = $data->fields->status->iconUrl;
        $issue->fields->status->name = $data->fields->status->name;
        $issue->fields->status->id = $data->fields->status->id;

        $issue->fields->priority = new IssuePriority;
        $issue->fields->priority->self = $data->fields->priority->self;
        $issue->fields->priority->iconUrl = $data->fields->priority->iconUrl;
        $issue->fields->priority->name = $data->fields->priority->name;
        $issue->fields->priority->id = $data->fields->priority->id;

        $issue->fields->type = new IssueType;
        $issue->fields->type->self = $data->fields->issuetype->self;
        $issue->fields->type->id = $data->fields->issuetype->id;
        $issue->fields->type->description = $data->fields->issuetype->description;
        $issue->fields->type->iconUrl = $data->fields->issuetype->iconUrl;
        $issue->fields->type->name = $data->fields->issuetype->name;
        $issue->fields->type->subTask = $data->fields->issuetype->subtask;
        $issue->fields->type->hierarchyLevel = $data->fields->issuetype->hierarchyLevel;

        $issue->fields->project = new Project;
        $issue->fields->project->id = $data->fields->project->id;
        $issue->fields->project->key = $data->fields->project->key;
        $issue->fields->project->name = $data->fields->project->name;

        $issue->fields->summary = $data->fields->summary;
        $issue->fields->description = $data->fields->description;

        return $issue;
    }
}