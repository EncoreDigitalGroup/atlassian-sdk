<?php

namespace EncoreDigitalGroup\Atlassian\Tests;

use EncoreDigitalGroup\Atlassian\Providers\AtlassianServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            AtlassianServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Set up environment variables or configuration specific to your package
        $app['config']->set('atlassian.hostname', 'https://example.atlassian.net');
        $app['config']->set('atlassian.username', 'expectedUsername');
        $app['config']->set('atlassian.token', 'expectedToken');

        $fakeIssue = [
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

        // Search Fake Issues
        Http::fake([
            'https://example.atlassian.net/rest/api/2/search*' => Http::response([
                "expand" => "schema,names",
                "startAt" => 0,
                "maxResults" => 50,
                "total" => 2,
                "issues" => [$fakeIssue],
            ]),
        ]);

        // Get Fake Issue
        Http::fake([
            'https://example.atlassian.net/rest/api/2/issue/10001' => Http::response($fakeIssue),
        ]);

        // Create Fake Issue
        Http::fake([
            'https://example.atlassian.net/rest/api/2/issue' => Http::response([
                'id' => '10001',
                'key' => 'TEST-1',
                'self' => 'https://example.atlassian.net/rest/api/issue/10001',
            ]),
        ]);
    }
}
