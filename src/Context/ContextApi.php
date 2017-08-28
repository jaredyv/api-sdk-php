<?php

namespace Smartling\Context;

use Psr\Log\LoggerInterface;
use Smartling\AuthApi\AuthApiInterface;
use Smartling\BaseApiAbstract;
use Smartling\Context\Params\UploadContextParameters;

/**
 * Class ContextApi
 *
 * @package Smartling\Project
 */
class ContextApi extends BaseApiAbstract
{

    const ENDPOINT_URL = 'https://api.smartling.com/context-api/v2/projects';

    /**
     * Instantiates Context API object.
     *
     * @param AuthApiInterface $authProvider
     * @param string $projectId
     * @param LoggerInterface $logger
     *
     * @return ContextApi
     */
    public static function create(AuthApiInterface $authProvider, $projectId, $logger = null)
    {
        $client = self::initializeHttpClient(self::ENDPOINT_URL);

        $instance = new self($projectId, $client, $logger, self::ENDPOINT_URL);
        $instance->setAuth($authProvider);

        return $instance;
    }

    /**
     * Upload a new context.
     *
     * @param \Smartling\Context\Params\UploadContextParameters $params
     * @return bool
     * @throws \Smartling\Exceptions\SmartlingApiException
     */
    public function uploadContext(UploadContextParameters $params)
    {
        $requestData = $this->getDefaultRequestData('body', $params->exportToArray());
        $requestData['headers']['Content-Type'] = 'application/json';
        $request = $this->prepareHttpRequest('contexts', $requestData, self::HTTP_METHOD_POST);

        return $this->sendRequest($request);
    }

  /**
   * Match context.
   *
   * @param $contextUid
   * @return bool
   * @throws \Smartling\Exceptions\SmartlingApiException
   */
    public function matchContext($contextUid)
    {
        $endpoint = vsprintf('contexts/%s/match/async', $contextUid);
        $requestData = $this->getDefaultRequestData('body', '');
        $requestData['headers']['Content-Type'] = 'application/json';
        $request = $this->prepareHttpRequest($endpoint, $requestData, self::HTTP_METHOD_POST);

        return $this->sendRequest($request);
    }

}
