<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\FieldCollection;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Traits\MapFields;

class JiraField
{
    use InteractsWithAtlassian;
    use MapFields;

    protected const string FIELD_ENDPOINT = '/rest/api/2/field';

    public function getAllFields(): FieldCollection
    {
        $fields = $this->client()->get($this->hostname . self::FIELD_ENDPOINT);

        $fieldCollection = new FieldCollection();

        foreach($fields as $field) {
            $mappedField = $this->mapFields($field);
            $fieldCollection->push($mappedField);
        }

        return $fieldCollection;
    }
}