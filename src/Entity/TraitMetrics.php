<?php

namespace Audiens\AdobeClient\Entity;

/**
 * Class TraitMetrics
 */
class TraitMetrics
{
    /** @var  \DateTime */
    protected $timestamp;

    /** @var  int */
    protected $count;

    /** @var  int */
    protected $uniques;

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getUniques()
    {
        return $this->uniques;
    }

    /**
     * @param int $uniques
     */
    public function setUniques($uniques)
    {
        $this->uniques = $uniques;
    }
}
