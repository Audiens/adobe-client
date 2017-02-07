<?php

namespace Test\unit;

use Audiens\AdobeClient\Auth;
use Audiens\AdobeClient\Authentication\AuthStrategyInterface;
use Test\TestCase;


/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{
    /**
     * @test
     */
    public function should_append_the_authorization_token_when_performing_any_request()
    {

        $client_id = 'sample_client_id';
        $secret_key = 'sample_secret_key';
        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $dummyStream = $this->prophesize(Stream::class);
        $dummyStream->getContents("{'response:{}'}");

        $dummyResponse = $this->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $authStrategy = $this->prophesize(AuthStrategyInterface::class);

        $authStrategy->authenticate($client_id, $secret_key,$username, $password, Argument::any())->willReturn($token)->shouldBeCalled();

        $expectedRequestOptions = [
            'headers' => [
                'Authorization' => $token,
            ],
        ];

        $client = $this->prophesize(ClientInterface::class);
        $client->request('POST', 'random_url', $expectedRequestOptions)->willReturn($dummyResponse->reveal())->shouldBeCalled();

        $auth = new Auth($client_id, $secret_key, $username, $password, $client->reveal(), $authStrategy->reveal());
        $auth->request('POST', 'random_url', []);


    }

    /**
     * @param $token
     *
     * @return Response
     */
    protected function getTokenResponse($token)
    {

        $responseBody = json_encode(
            [
                'response' => [
                    'access_token' => $token,
                ],
            ]
        );

        return new Response(200, [], $responseBody);

    }

}
