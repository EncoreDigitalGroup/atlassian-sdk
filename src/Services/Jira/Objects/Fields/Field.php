<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields;

class Field
{
    public ?string $id = null;
    public ?string $key = null;
    public ?string $name = null;
    public bool $custom = false;
    public bool $orderable = false;
    public bool $navigable = false;
    public bool $searchable = false;
}