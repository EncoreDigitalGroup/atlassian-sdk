<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues;

class Issue
{
    public ?string $expand;
    public ?string $id = null;
    public ?string $self = null;
    public ?string $key = null;
    public IssueFields $fields;

    public function __construct()
    {
        $this->fields = new IssueFields;
    }
}
