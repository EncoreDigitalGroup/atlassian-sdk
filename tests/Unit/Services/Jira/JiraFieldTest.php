<?php

use EncoreDigitalGroup\Atlassian\Services\Jira\JiraField;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\Fields\Field;
use Illuminate\Support\Collection;

test('get all fields', function () {
    $result = JiraField::make()->getAllFields();

    /** @var Field $field */
    $field = $result->first();

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($field)->toBeInstanceOf(Field::class)
        ->and($field->id)->toEqual('10000')
        ->and($field->key)->toEqual('10000')
        ->and($field->name)->toEqual('customfield_10000')
        ->and($field->custom)->toBeTrue()
        ->and($field->orderable)->toBeTrue()
        ->and($field->navigable)->toBeTrue()
        ->and($field->searchable)->toBeTrue();
});