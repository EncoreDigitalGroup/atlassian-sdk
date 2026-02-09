<?php


/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Service Desk request participant (reporter or user).
 *
 * @api
 * @experimental
 */
class ServiceDeskRequestParticipant
{
    public ?string $accountId = null;

    public ?string $name = null;

    public ?string $displayName = null;

    public ?string $emailAddress = null;

    public ?bool $active = null;

    public ?string $timeZone = null;
}
