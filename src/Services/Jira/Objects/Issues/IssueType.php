<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues;

/** @api */
class IssueType
{
    public ?string $self;
    public ?string $id;
    public ?string $description;
    public ?string $iconUrl;
    public ?string $name;
    public bool $subTask = false;
    public ?int $avatarId;
    public ?int $hierarchyLevel;
}
