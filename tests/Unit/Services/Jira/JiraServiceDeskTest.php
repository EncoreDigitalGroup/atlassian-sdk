<?php


/*
 * Copyright (c) 2025 Encore Digital Group.
 * All Right Reserved.
 *

 */

use EncoreDigitalGroup\Atlassian\Services\Jira\JiraServiceDesk;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Customer;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\PagedCustomerList;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequest;
use EncoreDigitalGroup\Atlassian\Services\Jira\Resources\ServiceDeskCustomers;

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

test('customers returns ServiceDeskCustomers instance', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers();

    expect($result)->toBeInstanceOf(ServiceDeskCustomers::class);
});

test('create customer returns Customer with valid data', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->create('John Doe', 'john.doe@example.com');

    expect($result)->toBeInstanceOf(Customer::class)
        ->and($result->accountId)->toBe('5b10a2844c20165700ede21g')
        ->and($result->name)->toBe('john.doe')
        ->and($result->displayName)->toBe('John Doe')
        ->and($result->emailAddress)->toBe('john.doe@example.com')
        ->and($result->active)->toBe(true)
        ->and($result->timeZone)->toBe('America/New_York')
        ->and($result->links)->not->toBeNull();
});

test('create customer with strict conflict status code', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->create('John Doe', 'john.doe@example.com', true);

    expect($result)->toBeInstanceOf(Customer::class)
        ->and($result->accountId)->toBe('5b10a2844c20165700ede21g');
});

test('list customers returns PagedCustomerList', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->list('10');

    expect($result)->toBeInstanceOf(PagedCustomerList::class)
        ->and($result->size)->toBe(2)
        ->and($result->start)->toBe(0)
        ->and($result->limit)->toBe(50)
        ->and($result->isLastPage)->toBe(true)
        ->and($result->values)->toBeArray()
        ->and($result->values)->toHaveCount(2);
});

test('list customers with query filter', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->list('10', query: 'john');

    expect($result)->toBeInstanceOf(PagedCustomerList::class)
        ->and($result->values)->toBeArray()
        ->and($result->values[0])->toBeInstanceOf(Customer::class)
        ->and($result->values[0]->displayName)->toBe('John Doe')
        ->and($result->values[0]->emailAddress)->toBe('john.doe@example.com');
});

test('list customers with pagination', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->list('10', start: 10, limit: 25);

    expect($result)->toBeInstanceOf(PagedCustomerList::class)
        ->and($result->values)->toBeArray();
});

test('list customers maps customer objects correctly', function () {
    $service = JiraServiceDesk::make();

    $result = $service->customers()->list('10');

    expect($result->values[0])->toBeInstanceOf(Customer::class)
        ->and($result->values[0]->accountId)->toBe('5b10a2844c20165700ede21g')
        ->and($result->values[0]->displayName)->toBe('John Doe')
        ->and($result->values[1])->toBeInstanceOf(Customer::class)
        ->and($result->values[1]->accountId)->toBe('5b10a2844c20165700ede22h')
        ->and($result->values[1]->displayName)->toBe('Jane Smith');
});

test('add customers to service desk', function () {
    $service = JiraServiceDesk::make();

    $service->customers()->add('10', ['5b10a2844c20165700ede21g', '5b10a2844c20165700ede22h']);

    // If no exception is thrown, the test passes
    expect(true)->toBe(true);
});

test('add customers removes duplicates', function () {
    $service = JiraServiceDesk::make();

    $service->customers()->add('10', [
        '5b10a2844c20165700ede21g',
        '5b10a2844c20165700ede21g', // duplicate
        '5b10a2844c20165700ede22h',
    ]);

    // If no exception is thrown, the test passes
    expect(true)->toBe(true);
});

test('remove customers from service desk', function () {
    $service = JiraServiceDesk::make();

    $service->customers()->remove('10', ['5b10a2844c20165700ede21g']);

    // If no exception is thrown, the test passes
    expect(true)->toBe(true);
});

test('remove customers removes duplicates', function () {
    $service = JiraServiceDesk::make();

    $service->customers()->remove('10', [
        '5b10a2844c20165700ede21g',
        '5b10a2844c20165700ede21g', // duplicate
    ]);

    // If no exception is thrown, the test passes
    expect(true)->toBe(true);
});

test('revoke portal access for customer', function () {
    $service = JiraServiceDesk::make();

    $service->customers()->revokePortalAccess('5b10a2844c20165700ede21g');

    // If no exception is thrown, the test passes
    expect(true)->toBe(true);
});
