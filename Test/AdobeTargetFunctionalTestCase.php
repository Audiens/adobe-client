<?php

namespace Test;

use Audiens\AdobeClient\AuthAdobeTarget;
use Audiens\AdobeClient\Authentication\JwtStrategy;
use Audiens\AdobeClient\Repository\Target\AudienceRepository;
use Doctrine\Common\Cache\FilesystemCache;
use Dotenv\Dotenv;
use GuzzleHttp\Client;

/**
 * Class AdobeTargetFunctionalTestCase
 */
class AdobeTargetFunctionalTestCase extends \PHPUnit\Framework\TestCase
{

    const REQUIRED_ENV = [
        'CLIENT_ID',
        'CLIENT_SECRET',
        'JWT_TOKEN',
    ];

    protected function setUp()
    {

        if (!$this->checkEnv()) {
            $this->markTestSkipped('cannotInitialize enviroment tests will be skipped');
        }

        parent::setUp();
    }


    /**
     * @return bool
     */
    private function checkEnv()
    {

        try {
            $dotenv = new Dotenv(__DIR__.'/../');
            $dotenv->load();
        } catch (\Exception $e) {
        }

        $env = true;

        foreach (self::REQUIRED_ENV as $requiredEnv) {
            if (!getenv($requiredEnv)) {
                $env = false;
            }
        }

        return $env;
    }


    /**
     * @param bool|true $cacheToken
     *
     * @return AuthAdobeTarget
     */
    protected function getAuth($cacheToken = true)
    {
        $cache = $cacheToken ? new FilesystemCache('build') : null;
        $client = new Client();

        $authStrategy = new JwtStrategy(new Client(), $cache);


        $authClient = new AuthAdobeTarget(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'), getenv('JWT_TOKEN'), $client, $authStrategy);

        return $authClient;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return AudienceRepository
     */
    protected function getAudienceRepository($cacheToken = true)
    {

        $authClient = $this->getAuth($cacheToken);

        $audienceRepository = new AudienceRepository($authClient);
        $audienceRepository->setBaseUrl(AudienceRepository::BASE_URL);

        return $audienceRepository;
    }

}
