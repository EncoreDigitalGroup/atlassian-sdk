<?php

namespace EncoreDigitalGroup\Atlassian\Tests;

use EncoreDigitalGroup\Atlassian\Providers\AtlassianServiceProvider;
use Illuminate\Foundation\Application;
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
    }
}
