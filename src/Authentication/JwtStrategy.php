<?php

namespace Audiens\AdobeClient\Authentication;

use Audiens\AdobeClient\Exception\AuthException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

/**
 * Class JwtStrategy
 */
class JwtStrategy implements AuthStrategyInterface
{

    const NAME = 'jwt_auth_strategy';

    const BASE_URL = 'https://ims-na1.adobelogin.com/ims/exchange/jwt/';

    const CACHE_NAMESPACE = 'jwt_auth_token';
    const TOKEN_EXPIRATION = 110;

    /** @var Cache */
    protected $cache;

    /**
     * JwtStrategy constructor.
     *
     * @param ClientInterface $clientInterface
     * @param Cache|null $cache
     */
    public function __construct(ClientInterface $clientInterface, Cache $cache)
    {
        $this->cache = $cache;
        $this->client = $clientInterface;
    }

    public function authenticate($clientId, $secretKey, $username, $password, $cache = true, $refresh = false)
    {
        // TODO: Implement authenticate() method.
    }

    public function authenticateJwtToken($clientId, $secretKey, $jwtToken, $cache = true)
    {
        $cacheKey = self::CACHE_NAMESPACE . sha1($clientId . $secretKey . $jwtToken . self::BASE_URL);

        if ($cache) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $response = $this->client->request(
            'POST',
            self::BASE_URL,
            [
                'form_params' =>
                    [
                        'client_id' => $clientId,
                        'client_secret' => $secretKey,
                        'jwt_token' => $jwtToken,
                    ]
            ]
        );


        $content = $response->getBody()->getContents();
        $response->getBody()->rewind();

        $contentArray = \json_decode($content, true);

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
