<?php

/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Container for Service Desk request field values.
 *
 * Handles both standard fields (summary, description) and custom fields (customfield_10001).
 *
 * @api
 *
 * @experimental
 */
class ServiceDeskRequestFieldValues
{
    /**
     * Field values as key-value pairs.
     *
     * @var array<string, mixed>
     */
    public array $fields = [];

    /**
     * Set a field value.
     */
    public function setField(string $fieldId, mixed $value): void
    {
        $this->fields[$fieldId] = $value;
    }

    /**
     * Get a field value.
     */
    public function getField(string $fieldId): mixed
    {
        return $this->fields[$fieldId] ?? null;
    }
}
