<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Issues;

class Issue
{
    public ?string $expand;
    public string $id;
    public string $self;
    public string $key;
    public IssueFields $fields;

    public function __construct()
    {
        $this->fields = new IssueFields();
    }
}
