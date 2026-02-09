<?php


namespace EncoreDigitalGroup\Atlassian\Services\Jira;

use EncoreDigitalGroup\Atlassian\Services\Jira\Common\InteractsWithAtlassian;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\ServiceDeskRequest;
use EncoreDigitalGroup\Atlassian\Services\Jira\Objects\ServiceDesk\Traits\MapServiceDeskRequests;

/**
 * Service for interacting with Jira Service Desk API.
 *
 * Enables creating and managing Service Desk customer requests via the
 * Atlassian Service Desk REST API.
 *
 * @api
 * @experimental
 */
class JiraServiceDesk
{
    use InteractsWithAtlassian;
    use MapServiceDeskRequests;

    public const string SERVICE_DESK_REQUEST_ENDPOINT = '/rest/servicedeskapi/request';

    /**
     * Create a new Service Desk customer request.
     */
    public function createRequest(ServiceDeskRequest $request): ServiceDeskRequest
    {
        $payload = $this->prepareRequestPayload($request);
        $response = $this->client()->post($this->hostname . self::SERVICE_DESK_REQUEST_ENDPOINT, $payload);
        $responseData = json_decode($response->body());

        return $this->mapServiceDeskRequest($responseData);
    }

    /**
     * Retrieve an existing Service Desk request by issue ID or key.
     */
    public function getRequest(string $issueIdOrKey): ServiceDeskRequest
    {
        $response = $this->client()->get($this->hostname . self::SERVICE_DESK_REQUEST_ENDPOINT . '/' . $issueIdOrKey);
        $responseData = json_decode($response->body());

        return $this->mapServiceDeskRequest($responseData);
    }

    /**
     * Prepare request payload for API submission.
     */
    private function prepareRequestPayload(ServiceDeskRequest $request): array
    {
        $payload = [
            'serviceDeskId' => $request->serviceDeskId,
            'requestTypeId' => $request->requestTypeId,
            'requestFieldValues' => $request->requestFieldValues->fields,
        ];

        if ($request->raiseOnBehalfOf !== null) {
            $payload['raiseOnBehalfOf'] = $request->raiseOnBehalfOf;
        }

        return $payload;
    }
}
