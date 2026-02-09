<?php

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk;

/**
 * Represents a paginated list of Service Desk customers
 *
 * @api
 * @experimental
 */
class PagedCustomerList
{
    /**
     * @param array<string>|null $expands The expands applied to the customer list
     * @param int|null $size The number of items in this page
     * @param int|null $start The starting index of the page
     * @param int|null $limit The maximum number of items per page
     * @param bool|null $isLastPage Whether this is the last page
     * @param array<string, mixed>|null $links HAL links for pagination
     * @param array<Customer>|null $values The customers in this page
     */
    public function __construct(
        public ?array $expands = null,
        public ?int   $size = null,
        public ?int   $start = null,
        public ?int   $limit = null,
        public ?bool  $isLastPage = null,
        public ?array $links = null,
        public ?array $values = null,
    ) {}
}
