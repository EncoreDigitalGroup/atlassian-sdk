<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Field;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Traits\MapFields;
use Illuminate\Support\Collection;

class JiraField
{
    use InteractsWithAtlassian;
    use MapFields;

    public const string FIELD_ENDPOINT = '/rest/api/2/field';

    /** @returns Collection<Field> */
    public function getAllFields(): Collection
    {
        $fields = $this->client()->get($this->hostname . self::FIELD_ENDPOINT);

        $fields = json_decode($fields->body());

        $fieldCollection = new Collection;

        foreach ($fields as $field) {
            $mappedField = $this->mapFields($field);
            $fieldCollection->push($mappedField);
        }

        return $fieldCollection;
    }
}