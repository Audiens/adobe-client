<?php

namespace Test\Functional;

use Audiens\AdobeClient\Entity\Traits;
use Prophecy\Argument;
use Test\FunctionalTestCase;

/**
 * Class TraitRepositoryTest
 */
class TraitRepositoryTest extends FunctionalTestCase
{


    /**
     * @test
     */
    public function findAll_will_return_a_list_of_trait()
    {
        $repository = $this->getTraitRepository();

        $traits = $repository->findAll();

        $this->assertGreaterThan(0, $traits);

        foreach ($traits as $trait) {
            $this->assertInstanceOf(Traits::class, $trait);
        }


    }

    /**
     * @test
     *
     */
    public function add_will_create_trait()
    {
        $trait = new Traits();

        $trait->setName('test');
        $trait->setDescription('test');
        $trait->setIntegrationCode("company-activity");
        $trait->setTraitType("COMPANY_ACTIVITY_TRAIT");
        $trait->setStatus("ACTIVE");
        $trait->setDataSourceId(113425);
        $trait->setFolderId(105946);

        $repository = $this->getTraitRepository();


        $repositoryResponse = $repository->add($trait);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());
        $this->assertNotNull($trait->getSid());
    }

    /**
     * @test
     */
    public function findOneByWillReturn_a_trait()
    {
        $id = 4976311;

        $repository = $this->getTraitRepository();

        $traits = $repository->findOneById($id);

        $this->assertNotEmpty($traits);

        $this->assertEquals($id, $traits->getSid());


    }
}