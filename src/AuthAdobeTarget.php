<?php

namespace Audiens\AdobeClient;

use Audiens\AdobeClient\Authentication\AuthStrategyInterface;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class Auth
 *
 */
class AuthAdobeTarget implements ClientInterface
{

    /** @var  Cache */
    protected $cache;

    /** @var  Client */
    protected $client;

    /** @var string */
    protected $token;

    /** @var string */

    /** @var  string */
    protected $clientId;

    /** @var  string */
    protected $secretKey;

    protected $authStrategy;

    /**
     * Auth constructor.
     * @param $clientId
     * @param $secretKey
     * @param $token
     * @param ClientInterface $clientInterface
     * @param AuthStrategyInterface $authStrategy
     */
    public function __construct(
        $clientId,
        $secretKey,
        $token,
        ClientInterface $clientInterface,
        AuthStrategyInterface $authStrategy
    ) {
        $this->clientId = $clientId;
        $this->secretKey = $secretKey;
        $this->token = $token;

        $this->client = $clientInterface;
        $this->authStrategy = $authStrategy;
    }


    /**
     *
     * This function is specific for adobe target request
     * @param       $method
     * @param null  $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri = null, array $options = [])
    {
        $optionForToken = [
            'headers' => [
                'Authorization' =>  ['Bearer '.$this->authStrategy->authenticateJwtToken($this->clientId, $this->secretKey, $this->token, true)],
                'content-type' => 'application/vnd.adobe.target.v1+json',
                'x-api-key' => $this->clientId
            ],
        ];

        $options = array_merge_recursive($options, $optionForToken);

        return $this->client->request($method, $uri, $options);
    }


    /**
     * @inheritDoc
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        return $this->client->sendAsync($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        return $this->client->requestAsync($method, $uri, $options);
    }

    /**
     * @inheritDoc
     */
    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }
}
