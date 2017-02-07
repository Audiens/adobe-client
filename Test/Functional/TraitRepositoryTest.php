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

        dump($traits);
        die;
        $this->assertGreaterThan(0, $traits);

        foreach ($traits as $trait) {
            $this->assertInstanceOf(Traits::class, $trait);
        }
    }
//
//    /**
//     * @test
//     */
//    public function findOneByWillReturn_a_trait()
//    {
//        $id = 4976311;
//
//        $repository = $this->getTraitRepository();
//
//        $traits = $repository->findOneById($id);
//
//        $this->assertNotEmpty($traits);
//
//        $this->assertEquals($id, $traits->getSid());
//    }

//    /**
//     * @test
//     */
//    public function getTrendByTrait()
//    {
//        $sid = 5584551;
//
//        $repository = $this->getTraitRepository();
//
//        $startDate = new \DateTime('-1 month');
//        $endDate = new \DateTime('now');
//
//        /** @var Traits[] $traits */
//        $traits = $repository->getTrendByTrait($sid, $startDate, $endDate, '1D');
//
//        dump($traits);
//        $this->assertGreaterThan(0, $traits);
//
//        /** @var Traits $trait */
//        foreach ($traits as $trait) {
//            dump($trait);
////            $this->assertInstanceOf(Traits::class, $trait);
////
////            $this->assertNotEmpty($trait->getMetrics());
//        }
//    }
}