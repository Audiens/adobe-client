<?php

namespace Test\unit;

use Audiens\AdobeClient\Authentication\SandboxStrategy;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class SandboxStrategyTest
 */
class SandboxStrategyTest extends TestCase
{

    /**
     * @test
     */
    public function should_make_a_post_to_the_sandbox_auth_endpoint()
    {

        $client_id = 'sample_client_id';
        $secret_key = 'sample_secret_key';
        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $fakeResponseContent = [
            "access_token" => $token,
            
        ];

        $headerAuth = base64_encode(
            sprintf(
                '%s:%s',
                $client_id,
                $secret_key
            )
        );

        $cache = $this->prophesize(Cache::class);

        $cache->contains(Argument::any())->willReturn(false)->shouldBeCalled();
        $cache->save(Argument::any(), $token, Argument::type('integer'))->shouldBeCalled();

        $dummyStream = $this->prophesize(Stream::class);
        $dummyStream->getContents()->willReturn(json_encode($fakeResponseContent));
        $dummyStream->rewind()->shouldBeCalled();

        $dummyResponse = $this->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());


        $client = $this->prophesize(ClientInterface::class);

        $client
            ->request('POST', SandboxStrategy::BASE_URL, [
                    'headers' =>
                        [
                            'Authorization' => 'Basic ' . $headerAuth
                        ],
                    'form_params' =>
                        [
                            'grant_type' => 'password',
                            'username' => $username,
                            'password' => $password,
                        ]
                ]
            )
            ->willReturn($dummyResponse->reveal());

        $auth = new SandboxStrategy($client->reveal(), $cache->reveal());
        $auth->authenticate($client_id, $secret_key, $username, $password, true);


    }

}
