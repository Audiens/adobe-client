<?php

namespace Audiens\AdobeClient\Authentication;

use Audiens\AdobeClient\Exceptions\AuthException;

/**
 * Class AuthStrategyInterface
 */
interface AuthStrategyInterface
{

    /**
     * @param $clientId
     * @param $secretKey
     * @param $username
     * @param $password
     * @param bool $cache
     * @param bool $refresh
     * @return string the token
     */
    public function authenticate($clientId, $secretKey, $username, $password, $cache = true, $refresh = false);

    /**
     * @param      $clientId
     * @param      $clientSecret
     * @param      $jwtToken
     * @param bool $cache
     * @return string bearer
     */
    public function authenticateJwtToken($clientId, $clientSecret, $jwtToken, $cache = true);

    /**
     * @return string
     */
    public function getSlug();
}
