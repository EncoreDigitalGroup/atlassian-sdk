<?php

/*
 * Copyright (c) 2025. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Tests;

use EncoreDigitalGroup\Atlassian\Providers\AtlassianServiceProvider;
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraField;
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PHPGenesis\Http\HttpClient;

class TestCase extends OrchestraTestCase
{
    protected const string HOSTNAME = 'https://example.atlassian.net';

    protected function getPackageProviders($app): array
    {
        return [
            AtlassianServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {

        $app->singleton('http', function () {
            return new HttpFactory();
        });

        Facade::setFacadeApplication($app);

        // Set up environment variables or configuration specific to your package
        $app['config']->set('atlassian.hostname', self::HOSTNAME);
        $app['config']->set('atlassian.username', 'expectedUsername');
        $app['config']->set('atlassian.token', 'expectedToken');

        $this->setupSearchIssues();
        $this->setupGetIssue();
        $this->setupCreateIssue();
        $this->setupGetAllFields();
        $this->setupCreateServiceDeskRequest();
        $this->setupGetServiceDeskRequest();
        $this->setupCreateCustomer();
        $this->setupListCustomers();
        $this->setupAddCustomers();
        $this->setupRemoveCustomers();
        $this->setupRevokePortalAccess();
    }

    private function setupSearchIssues(): void
    {
        // Search Fake Issues
        HttpClient::fake([
            self::HOSTNAME . JiraProject::ISSUE_SEARCH_ENDPOINT . '*' => HttpClient::response([
                "expand" => "schema,names",
                "nextPageToken" => null,
                "maxResults" => 50,
                "total" => 2,
                "issues" => [$this->getFakeIssue()],
            ]),
        ]);
    }

    private function setupGetIssue(): void
    {
        // Get Fake Issue
        HttpClient::fake([
            self::HOSTNAME . JiraProject::ISSUE_ENDPOINT . '/10001' => HttpClient::response($this->getFakeIssue()),
        ]);
    }

    private function setupCreateIssue(): void
    {
        HttpClient::fake([
            self::HOSTNAME . JiraProject::ISSUE_ENDPOINT => HttpClient::response([
                'id' => '10001',
                'key' => 'TEST-1',
                'self' => 'https://example.atlassian.net/rest/api/issue/10001',
            ]),
        ]);
    }

    private function setupGetAllFields(): void
    {
        HttpClient::fake([
            self::HOSTNAME . JiraField::FIELD_ENDPOINT => HttpClient::response([$this->getFakeFields()]),
        ]);
    }

    private function getFakeFields(): array
    {
        return [
            'id' => '10000',
            'key' => '10000',
            'name' => 'customfield_10000',
            'custom' => true,
            'orderable' => true,
            'navigable' => true,
            'searchable' => true,
        ];
    }

    private function getFakeIssue(): array
    {
        return [
            "expand" => "",
            "id" => "10001",
            "self" => "https://example.atlassian.net/rest/api/2/issue/10001",
            "key" => "TEST-1",
            "fields" => [
                "summary" => "Test Issue 1",
                "description" => "This is a test issue",
                "status" => [
                    "self" => "https://example.atlassian.net/rest/api/2/status/10001",
                    "description" => "Issue is open and ready for the assignee to start work on it.",
                    "iconUrl" => "https://example.atlassian.net/",
                    "name" => "Open",
                    "id" => "10001",
                ],
                "priority" => [
                    "self" => "https://example.atlassian.net/rest/api/2/priority/3",
                    "iconUrl" => "https://example.atlassian.net/images/icons/priorities/medium.svg",
                    "name" => "Medium",
                    "id" => "3",
                ],
                "issuetype" => [
                    "self" => "https://example.atlassian.net/rest/api/2/issuetype/10001",
                    "id" => "10001",
                    "description" => "",
                    "iconUrl" => "https://example.atlassian.net/rest/api/2/universal_avatar/view/type/issuetype/avatar/10001?size=medium",
                    "name" => "Technical Debt",
                    "subtask" => false,
                    "avatarId" => 10001,
                    "hierarchyLevel" => 0,
                ],
                "project" => [
                    "self" => "https://example.atlassian.net/rest/api/2/project/1001",
                    "id" => "10001",
                    "key" => "TEST",
                    "name" => "TEST PROJECT",
                    "projectTypeKey" => "software",
                    "simplified" => false,
                ],
            ],
        ];
    }

    private function setupCreateServiceDeskRequest(): void
    {
        HttpClient::fake([
            self::HOSTNAME . JiraServiceDesk::SERVICE_DESK_REQUEST_ENDPOINT => HttpClient::response($this->getFakeServiceDeskRequest()),
        ]);
    }

    private function setupGetServiceDeskRequest(): void
    {
        HttpClient::fake([
            self::HOSTNAME . JiraServiceDesk::SERVICE_DESK_REQUEST_ENDPOINT . '/SD-1' => HttpClient::response($this->getFakeServiceDeskRequest()),
        ]);
    }

    private function getFakeServiceDeskRequest(): array
    {
        return [
            'issueId' => '10001',
            'issueKey' => 'SD-1',
            'requestTypeId' => '25',
            'serviceDeskId' => '10',
            'createdDate' => [
                'iso8601' => '2025-02-08T10:00:00+0000',
                'jira' => '2025-02-08T10:00:00.000+0000',
                'friendly' => '08/Feb/25 10:00 AM',
                'epochMillis' => 1707390000000,
            ],
            'reporter' => [
                'accountId' => '5b10a2844c20165700ede21g',
                'name' => 'test.user',
                'displayName' => 'Test User',
                'emailAddress' => 'test.user@example.com',
                'active' => true,
                'timeZone' => 'Australia/Sydney',
            ],
            'requestFieldValues' => [
                [
                    'fieldId' => 'summary',
                    'label' => 'What do you need?',
                    'value' => 'Test Service Desk Request',
                ],
                [
                    'fieldId' => 'description',
                    'label' => 'Why do you need this?',
                    'value' => 'This is a test service desk request',
                ],
            ],
            'sla' => [
                [
                    'id' => '1',
                    'name' => 'Time to resolution',
                    'completedCycle' => false,
                    'remainingTime' => [
                        'millis' => 28800000,
                        'friendly' => '8h',
                    ],
                ],
            ],
        ];
    }

    private function setupCreateCustomer(): void
    {
        HttpClient::fake([
            self::HOSTNAME . '/rest/servicedeskapi/customer' => HttpClient::response($this->getFakeCustomer()),
        ]);
    }

    private function setupListCustomers(): void
    {
        HttpClient::fake([
            self::HOSTNAME . '/rest/servicedeskapi/servicedesk/*/customer*' => HttpClient::response($this->getFakeCustomerList()),
        ]);
    }

    private function setupAddCustomers(): void
    {
        HttpClient::fake([
            self::HOSTNAME . '/rest/servicedeskapi/servicedesk/*/customer' => HttpClient::response(null, 204),
        ]);
    }

    private function setupRemoveCustomers(): void
    {
        HttpClient::fake([
            self::HOSTNAME . '/rest/servicedeskapi/servicedesk/*/customer' => HttpClient::response(null, 204),
        ]);
    }

    private function setupRevokePortalAccess(): void
    {
        HttpClient::fake([
            self::HOSTNAME . '/rest/servicedeskapi/customer/user/*/revoke-portal-only-access' => HttpClient::response(null, 204),
        ]);
    }

    private function getFakeCustomer(): array
    {
        return [
            'accountId' => '5b10a2844c20165700ede21g',
            'name' => 'john.doe',
            'key' => 'john.doe',
            'displayName' => 'John Doe',
            'emailAddress' => 'john.doe@example.com',
            'active' => true,
            'timeZone' => 'America/New_York',
            '_links' => [
                'jiraRest' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede21g',
                'avatarUrls' => [
                    '48x48' => 'https://example.atlassian.net/avatar.png',
                ],
                'self' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede21g',
            ],
        ];
    }

    private function getFakeCustomerList(): array
    {
        return [
            '_expands' => [],
            'size' => 2,
            'start' => 0,
            'limit' => 50,
            'isLastPage' => true,
            '_links' => [
                'base' => 'https://example.atlassian.net/rest/servicedeskapi',
                'context' => 'context',
                'next' => 'https://example.atlassian.net/rest/servicedeskapi/servicedesk/10/customer?start=2',
                'prev' => '',
            ],
            'values' => [
                [
                    'accountId' => '5b10a2844c20165700ede21g',
                    'name' => 'john.doe',
                    'key' => 'john.doe',
                    'displayName' => 'John Doe',
                    'emailAddress' => 'john.doe@example.com',
                    'active' => true,
                    'timeZone' => 'America/New_York',
                    '_links' => [
                        'jiraRest' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede21g',
                        'self' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede21g',
                    ],
                ],
                [
                    'accountId' => '5b10a2844c20165700ede22h',
                    'name' => 'jane.smith',
                    'key' => 'jane.smith',
                    'displayName' => 'Jane Smith',
                    'emailAddress' => 'jane.smith@example.com',
                    'active' => true,
                    'timeZone' => 'Europe/London',
                    '_links' => [
                        'jiraRest' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede22h',
                        'self' => 'https://example.atlassian.net/rest/api/2/user?accountId=5b10a2844c20165700ede22h',
                    ],
                ],
            ],
        ];
    }
}
