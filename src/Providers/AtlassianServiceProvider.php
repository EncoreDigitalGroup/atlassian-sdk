<?php


namespace EncoreDigitalGroup\Atlassian\Providers;

use Illuminate\Support\ServiceProvider;

class AtlassianServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/atlassian.php', 'atlassian');
    }

    public function boot(): void {}
}
