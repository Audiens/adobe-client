<?php

namespace Audiens\AdobeClient\Authentication;

use Audiens\AdobeClient\Exception\AuthException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

/**
 * Class AdnxStrategy
 */
class AdnxStrategy implements AuthStrategyInterface
{

    const NAME = 'adnx_auth_strategy';

    const BASE_URL = 'https://api.demdex.com/oauth/token';

    const CACHE_NAMESPACE = 'adnx_auth_token';
    const TOKEN_EXPIRATION = 110;

    /** @var Cache */
    protected $cache;

    /**
     * AdnxStrategy constructor.
     *
     * @param ClientInterface $clientInterface
     * @param Cache|null $cache
     */
    public function __construct(ClientInterface $clientInterface, Cache $cache)
    {
        $this->cache = $cache;
        $this->client = $clientInterface;
    }

    /**
     * @param string $username
     * @param string $password
     * @param bool $cache
     * @param bool $refresh
     * @return mixed
     * @throws AuthException
     */
    public function authenticate($clientId, $secretKey, $username, $password, $cache = true, $refresh = false)
    {
        $cacheKey = self::CACHE_NAMESPACE . sha1($username . $password . self::BASE_URL);

        if ($cache) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $headerAuth = base64_encode(
            sprintf(
                '%s:%s',
                $clientId,
                $secretKey
            )
        );

        $response = $this->client->request(
            'POST',
            self::BASE_URL,
            [
                'headers' =>
                    [
                        'Authorization' => 'Basic ' . $headerAuth
                    ],
                'form_params' =>
                    [
                        'grant_type' => $refresh ? 'refresh_token' : 'password',
                        'username' => $username,
                        'password' => $password,
                    ]
            ]
        );


        $content = $response->getBody()->getContents();
        $response->getBody()->rewind();

        $contentArray = json_decode($content, true);

        if (!isset($contentArray["access_token"])) {
            throw new \Exception(AuthException::authFailed('No field access token available in json response'));
        }

        $token = $contentArray["access_token"];

        if ($cache) {
            $this->cache->save($cacheKey, $token, self::TOKEN_EXPIRATION);
        }

        return $token;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return self::NAME;
    }
}
