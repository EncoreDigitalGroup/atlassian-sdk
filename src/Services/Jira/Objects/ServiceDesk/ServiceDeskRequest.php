<?php

/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Service Desk customer request object.
 *
 * @api
 *
 * @experimental
 */
class ServiceDeskRequest
{
    public ?string $issueId = null;
    public ?string $issueKey = null;
    public ?string $requestTypeId = null;
    public ?string $serviceDeskId = null;
    public ServiceDeskRequestFieldValues $requestFieldValues;
    public ?ServiceDeskRequestParticipant $reporter = null;
    public ?string $raiseOnBehalfOf = null;

    /**
     * @var ServiceDeskRequestSla[]
     */
    public array $sla = [];

    public ?string $createdDate = null;

    public function __construct()
    {
        $this->requestFieldValues = new ServiceDeskRequestFieldValues;
    }
}
