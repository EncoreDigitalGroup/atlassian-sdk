<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Represents a Jira Service Desk customer
 *
 * @api
 * @experimental
 */
class Customer
{
    /**
     * @param string|null $accountId The account ID of the customer
     * @param string|null $name The username of the customer
     * @param string|null $key The customer key (deprecated by Atlassian)
     * @param string|null $displayName The display name of the customer
     * @param string|null $emailAddress The email address of the customer
     * @param bool|null $active Whether the customer account is active
     * @param string|null $timeZone The time zone of the customer
     * @param array<string, mixed>|null $links HAL links for the customer
     */
    public function __construct(
        public ?string $accountId = null,
        public ?string $name = null,
        public ?string $key = null,
        public ?string $displayName = null,
        public ?string $emailAddress = null,
        public ?bool   $active = null,
        public ?string $timeZone = null,
        public ?array  $links = null,
    ) {}
}
