<?php

namespace EncoreDigitalGroup\Atlassian\Tests;

use EncoreDigitalGroup\Atlassian\Providers\AtlassianServiceProvider;
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraField;
use EncoreDigitalGroup\Atlassian\Services\Jira\JiraProject;
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
    }

    private function setupSearchIssues(): void
    {
        // Search Fake Issues
        HttpClient::fake([
            self::HOSTNAME . JiraProject::ISSUE_SEARCH_ENDPOINT . '*' => HttpClient::response([
                "expand" => "schema,names",
                "startAt" => 0,
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
}
