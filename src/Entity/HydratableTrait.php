<?php

namespace Audiens\AdobeClient\Entity;

use Zend\Hydrator\ObjectProperty;
use Zend\Hydrator\Reflection;
use Zend\Stdlib\Hydrator;

/**
 * Class HydratableTrait
 */
trait HydratableTrait
{
    /**
     * @param array $objectArray
     *
     * @return self
     */
    public static function fromArray(array $objectArray)
    {

        $object = new self();
        self::getHydrator()->hydrate($objectArray, $object);

        return $object;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return self::getHydrator()->extract($this);
    }

    /**
     * @return ObjectProperty
     */
    private static function getHydrator()
    {
        $hydrator = new Reflection();

        return $hydrator;
    }
}
