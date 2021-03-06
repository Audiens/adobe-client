<?php

namespace Test;

use Audiens\AdobeClient\Auth;
use Audiens\AdobeClient\Authentication\AdnxStrategy;
use Audiens\AdobeClient\Authentication\SandboxStrategy;
use Audiens\AdobeClient\Repository\TraitRepository;
use Doctrine\Common\Cache\FilesystemCache;
use Dotenv\Dotenv;
use GuzzleHttp\Client;

/**
 * Class FunctionalTestCase
 */
class FunctionalTestCase extends \PHPUnit\Framework\TestCase
{

    const REQUIRED_ENV = [
        'CLIENT_ID',
        'SECRET_KEY',
        'USERNAME',
        'PASSWORD',
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
     * @return Auth
     */
    protected function getAuth($cacheToken = true)
    {
        $cache = $cacheToken ? new FilesystemCache('build') : null;
        $client = new Client();

        $authStrategy = new SandboxStrategy(new Client(), $cache);


        $authClient = new Auth(getenv('CLIENT_ID'), getenv('SECRET_KEY'), getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return TraitRepository
     */
    protected function getTraitRepository($cacheToken = true)
    {

        $authClient = $this->getAuth($cacheToken);

        $traitRepository = new TraitRepository($authClient);
        $traitRepository->setBaseUrl(TraitRepository::SANDBOX_BASE_URL);
        $traitRepository->setTrendUrl(TraitRepository::SANDBOX_TREND_URL);

        return $traitRepository;
    }

}
