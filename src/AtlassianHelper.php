<?php


namespace EncoreDigitalGroup\Atlassian;

class AtlassianHelper
{
    public static function getHostname(): string
    {
        return config('atlassian.hostname');
    }

    public static function getUsername(): string
    {
        return config('atlassian.username');
    }

    public static function getToken(): string
    {
        return config('atlassian.token');
    }
}
