<?php

namespace Audiens\AdobeClient;

use Audiens\AdobeClient\Authentication\AuthStrategyInterface;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Class Auth
 *
 */
class Auth implements ClientInterface
{

    /** @var  Cache */
    protected $cache;

    /** @var  Client */
    protected $client;

    /** @var string */
    protected $token;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var  string */
    protected $clientId;

    /** @var  string */
    protected $secretKey;

    protected $authStrategy;

    /**
     * Auth constructor.
     * @param $clientId
     * @param $secretKey
     * @param $username
     * @param $password
     * @param ClientInterface $clientInterface
     * @param AuthStrategyInterface $authStrategy
     */
    public function __construct(
        $clientId,
        $secretKey,
        $username,
        $password,
        ClientInterface $clientInterface,
        AuthStrategyInterface $authStrategy
    ) {
        $this->clientId = $clientId;
        $this->secretKey = $secretKey;
        $this->username = $username;
        $this->password = $password;

        $this->client = $clientInterface;
        $this->authStrategy = $authStrategy;
    }

    /**
     * @param string $method
     * @param null   $uri
     * @param array  $options
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function request($method, $uri = null, array $options = [])
    {

        $optionForToken = [
            'headers' => [
                'Authorization' =>  ['Bearer '.$this->authStrategy->authenticate($this->clientId, $this->secretKey, $this->username, $this->password)],
            ],
        ];


        $options = array_merge_recursive($options, $optionForToken);

        $response = $this->client->request($method, $uri, $options);

        if (!$this->needToRevalidate($response)) {
            return $response;
        }

        $optionForToken = [
            'headers' => [
                'Authorization' =>  ['Bearer '.$this->authStrategy->authenticate($this->clientId, $this->secretKey, $this->username, $this->password, true, true)],
            ],
        ];

        $options = array_merge($options, $optionForToken);

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


    /**
     * @param Response $response
     *
     * @return bool
     */
    protected function needToRevalidate(Response $response)
    {

        if ($response->getStatusCode() == 401) {
            $headers = $response->getHeaders();

            if (!empty($headers['WWW-Authenticate'])) {
                return strpos($headers['WWW-Authenticate'], 'invalid_token') === false;
            }
        }

        return false;
    }
}
