<?php

namespace Test\unit;

use Audiens\AdobeClient\Authentication\AdnxStrategy;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class AuthTest
 */
class AdnxStrategyTest extends TestCase
{

    /**
     * @test
     */
    public function should_make_a_post_to_the_auth_endpoint()
    {

        $client_id = 'sample_client_id';
        $secret_key = 'sample_secret_key';
        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $fakeResponseContent = [
            "response" => [
                "access_token" => $token,
            ],
        ];

        $cache = $this->prophesize(Cache::class);

        $cache->contains(Argument::any())->willReturn(false)->shouldBeCalled();
        $cache->save(Argument::any(), $token, Argument::type('integer'))->shouldBeCalled();

        $dummyStream = $this->prophesize(Stream::class);
        $dummyStream->getContents()->willReturn(json_encode($fakeResponseContent));
        $dummyStream->rewind()->shouldBeCalled();

        $dummyResponse = $this->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $payload = [
            'auth' => [
                'username' => $username,
                'password' => $password,
            ],
        ];

        $client = $this->prophesize(ClientInterface::class);

        $client
            ->request('POST', AdnxStrategy::BASE_URL, ['body' => json_encode($payload)])
            ->willReturn($dummyResponse->reveal());

        $auth = new AdnxStrategy($client->reveal(), $cache->reveal());
        $auth->authenticate($client_id, $secret_key, $username, $password, true);


    }

}
