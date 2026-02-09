<?php


/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequest;

test('make returns instance of JiraServiceDesk', function () {
    $service = JiraServiceDesk::make();

    expect($service)->toBeInstanceOf(JiraServiceDesk::class);
});

test('createRequest returns ServiceDeskRequest with valid data', function () {
    $service = JiraServiceDesk::make();

    $request = new ServiceDeskRequest();
    $request->serviceDeskId = '10';
    $request->requestTypeId = '25';
    $request->requestFieldValues->setField('summary', 'Test Service Desk Request');
    $request->requestFieldValues->setField('description', 'This is a test service desk request');

    $result = $service->createRequest($request);

    expect($result)->toBeInstanceOf(ServiceDeskRequest::class)
        ->and($result->issueId)->toBe('10001')
        ->and($result->issueKey)->toBe('SD-1')
        ->and($result->requestTypeId)->toBe('25')
        ->and($result->serviceDeskId)->toBe('10')
        ->and($result->requestFieldValues->getField('summary'))->toBe('Test Service Desk Request')
        ->and($result->requestFieldValues->getField('description'))->toBe('This is a test service desk request')
        ->and($result->reporter)->not->toBeNull()
        ->and($result->reporter->accountId)->toBe('5b10a2844c20165700ede21g')
        ->and($result->reporter->displayName)->toBe('Test User')
        ->and($result->createdDate)->toBe('2025-02-08T10:00:00+0000')
        ->and($result->sla)->toHaveCount(1)
        ->and($result->sla[0]->name)->toBe('Time to resolution');
});

test('getRequest returns ServiceDeskRequest for existing request', function () {
    $service = JiraServiceDesk::make();

    $result = $service->getRequest('SD-1');

    expect($result)->toBeInstanceOf(ServiceDeskRequest::class)
        ->and($result->issueId)->toBe('10001')
        ->and($result->issueKey)->toBe('SD-1')
        ->and($result->requestTypeId)->toBe('25')
        ->and($result->serviceDeskId)->toBe('10')
        ->and($result->requestFieldValues->getField('summary'))->toBe('Test Service Desk Request')
        ->and($result->reporter)->not->toBeNull()
        ->and($result->reporter->emailAddress)->toBe('test.user@example.com');
});
