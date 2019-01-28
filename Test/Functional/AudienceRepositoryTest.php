<?php

namespace Test\Functional;

use Audiens\AdobeClient\Entity\Target\Audience;
use Test\AdobeTargetFunctionalTestCase;

/**
 * Class AudienceRepositoryTest
 */
class AudienceRepositoryTest extends AdobeTargetFunctionalTestCase
{
    /**
     * @test
     */
    public function findAll_will_return_a_list_of_audiences()
    {
        $this->markTestSkipped('Please insert a valid tenant string');

        $tenant = '';

        $repository = $this->getAudienceRepository();

        $audiences = $repository->findAll($tenant);

        $this->assertGreaterThan(0, $audiences);

        foreach ($audiences as $audience) {
            $this->assertInstanceOf(Audience::class, $audience);
        }
    }

    /**
     * @test
     */
    public function findOneBy_will_return_the_correct_audiences()
    {
        $this->markTestSkipped('Please insert a valid tenant string');

        $tenant = '';

        $repository = $this->getAudienceRepository();

        $audience = $repository->findOneById(2196402, $tenant);

        $this->assertInstanceOf(Audience::class, $audience);
    }

    /**
     * @test
     */
    public function fromArray_will_translate_the_rule_correctly()
    {
        $jsonAudience = '
          {
            "name": "Homepage visitors from California",
            "description":"Description for my audience",
            "targetRule": {
                "and": [
                    {
                        "page": "url",
                        "equals":[
                            "http://www.mynewhomepage.com/"
                        ]
                    },
                    {
                        "geo": "region",
                        "matches": [
                            "california"
                        ]
                    }
                ]
            }
        }';

        $data = \json_decode($jsonAudience, true);

        $audience = Audience::fromArray($data);

        $this->assertEquals($audience->getName(), 'Homepage visitors from California');
        $this->assertEquals($audience->getDescription(), 'Description for my audience');
        $this->assertEquals($audience->getTargetRuleLogicalCondition(), 'and');
        $this->assertCount(2, $audience->getTargetRuleConditions());
    }

    /**
     * @test
     */
    public function toArray_will_translate_the_audience_to_a_valid_json()
    {
        $data = [];

        $data[0]['page'] = 'url';
        $data[0]['equals'] = [
            "www.mynewhomepage.com"
        ];

        $data[1]['geo'] = 'region';
        $data[1]['matches'] = [
            "california"
        ];

        $audience = new Audience();

        $audience->setName('Homepage visitors from California');
        $audience->setDescription('Description for my audience');
        $audience->setTargetRuleLogicalCondition('or');
        $audience->setTargetRuleConditions($data);

        $json = \json_encode($audience->toArray());

        $jsonAudience = '
          {
            "name":"Homepage visitors from California",
            "description":"Description for my audience",
            "targetRule":{
                "or":[
                    {
                        "page":"url",
                        "equals":[
                            "www.mynewhomepage.com"
                        ]
                    },
                    {
                        "geo":"region",
                        "matches":[
                            "california"
                        ]
                    }
                ]
            }
        }';

        $jsonFormatted = trim(preg_replace('/\s\s+/', '', $jsonAudience));
        $actualJsonFormatted = trim(preg_replace('/\s\s+/', '', $json));


        $this->assertEquals($jsonFormatted, $actualJsonFormatted);
    }




}
