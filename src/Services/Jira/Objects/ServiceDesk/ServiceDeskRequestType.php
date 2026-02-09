<?php

/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Service Desk request type metadata.
 *
 * @api
 *
 * @experimental
 */
class ServiceDeskRequestType
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $helpText = null;
    public ?string $issueTypeId = null;
    public ?string $serviceDeskId = null;
}
