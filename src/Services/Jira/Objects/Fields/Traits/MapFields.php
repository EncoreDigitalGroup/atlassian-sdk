<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Field;

trait MapFields
{
    private function mapFields(mixed $data): Field
    {
        $field = new Field;
        $field->id = $data->id;
        $field->key = $data->key;
        $field->name = $data->name;
        $field->custom = $data->custom;
        $field->orderable = $data->orderable;
        $field->navigable = $data->navigable;
        $field->searchable = $data->searchable;

        return $field;
    }
}