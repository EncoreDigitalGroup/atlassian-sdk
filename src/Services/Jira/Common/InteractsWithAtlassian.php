<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira\Common;

use EncoreDigitalGroup\Atlassian\AtlassianHelper;
use EncoreDigitalGroup\Atlassian\Helpers\AuthHelper;
use Illuminate\Http\Client\PendingRequest;
use PHPGenesis\Http\HttpClient;
use PHPGenesis\Http\HttpClientBuilder;

trait InteractsWithAtlassian
{
    public function __construct(public string $hostname, public string $username, public string $token)
    {
        $this->hostname = $hostname !== '' && $hostname !== '0' ? $hostname : AtlassianHelper::getHostname();
        $this->username = $username !== '' && $username !== '0' ? $username : AtlassianHelper::getUsername();
        $this->token = $token !== '' && $token !== '0' ? $token : AtlassianHelper::getToken();

        new HttpClientBuilder();
    }

    public static function make(?string $hostname = null, ?string $username = null, ?string $token = null): static
    {
        return new static(
            $hostname !== null && $hostname !== '' && $hostname !== '0' ? $hostname : AtlassianHelper::getHostname(),
            $username !== null && $username !== '' && $username !== '0' ? $username : AtlassianHelper::getUsername(),
            $token !== null && $token !== '' && $token !== '0' ? $token : AtlassianHelper::getToken()
        );
    }

    public function client(): PendingRequest
    {
        AuthHelper::validate($this);

        return HttpClient::withBasicAuth($this->username, $this->token);
    }
}