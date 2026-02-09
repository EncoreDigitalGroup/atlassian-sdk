<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Service Desk request SLA information.
 *
 * @api
 * @experimental
 */
class ServiceDeskRequestSla
{
    public ?string $id = null;

    public ?string $name = null;

    public ?bool $completedCycle = null;

    public ?array $remainingTime = null;
}
