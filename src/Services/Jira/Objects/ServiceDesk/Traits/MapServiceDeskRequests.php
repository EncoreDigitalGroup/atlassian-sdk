<?php


/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Traits;

use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequest;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequestParticipant;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequestSla;

/**
 * Trait for mapping Service Desk API responses to domain objects.
 *
 * @internal
 */
trait MapServiceDeskRequests
{
    /**
     * Map API response data to ServiceDeskRequest object.
     */
    private function mapServiceDeskRequest(mixed $data): ServiceDeskRequest
    {
        $request = new ServiceDeskRequest();
        $request->issueId = $data->issueId ?? null;
        $request->issueKey = $data->issueKey ?? null;
        $request->requestTypeId = $data->requestTypeId ?? null;
        $request->serviceDeskId = $data->serviceDeskId ?? null;
        $request->createdDate = $data->createdDate->iso8601 ?? $data->createdDate ?? null;

        $this->mapFieldValues($data, $request);
        $this->mapReporter($data, $request);
        $this->mapSlaInformation($data, $request);

        return $request;
    }

    /**
     * Map field values from API response.
     */
    private function mapFieldValues(mixed $data, ServiceDeskRequest $request): void
    {
        if (isset($data->requestFieldValues)) {
            foreach ($data->requestFieldValues as $fieldId => $value) {
                $request->requestFieldValues->setField($fieldId, $value);
            }
        }
    }

    /**
     * Map reporter information from API response.
     */
    private function mapReporter(mixed $data, ServiceDeskRequest $request): void
    {
        if (isset($data->reporter)) {
            $reporter = new ServiceDeskRequestParticipant();
            $reporter->accountId = $data->reporter->accountId ?? null;
            $reporter->name = $data->reporter->name ?? null;
            $reporter->displayName = $data->reporter->displayName ?? null;
            $reporter->emailAddress = $data->reporter->emailAddress ?? null;
            $reporter->active = $data->reporter->active ?? null;
            $reporter->timeZone = $data->reporter->timeZone ?? null;
            $request->reporter = $reporter;
        }
    }

    /**
     * Map SLA information from API response.
     */
    private function mapSlaInformation(mixed $data, ServiceDeskRequest $request): void
    {
        if (isset($data->sla) && is_array($data->sla)) {
            foreach ($data->sla as $slaData) {
                $sla = new ServiceDeskRequestSla();
                $sla->id = $slaData->id ?? null;
                $sla->name = $slaData->name ?? null;
                $sla->completedCycle = $slaData->completedCycle ?? null;
                $sla->remainingTime = isset($slaData->remainingTime) ? (array)$slaData->remainingTime : null;
                $request->sla[] = $sla;
            }
        }
    }
}
