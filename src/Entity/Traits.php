<?php

namespace Audiens\AdobeClient\Entity;

use Audiens\AdobeClient\Exceptions\AuthException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

/**
 * Class Traits
 */
class Traits
{

    use HydratableTrait;

    /** @var  int */
    protected $sid;

    /** @var  int */
    protected $pid;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $description;

    /** @var  string */
    protected $traitType;

    /** @var  string */
    protected $status;

    /** @var  int */
    protected $crUid;

    /** @var  int */
    protected $upUid;

    /** @var  string */
    protected $integrationCode;

    /** @var  int */
    protected $dataSourceId;

    /** @var  int */
    protected $folderId;

    /** @var  int */
    protected $count90Day;

    /** @var  int */
    protected $uniques90Day;

    /** @var  int */
    protected $uniques1Day;

    /** @var  int */
    protected $uniques14Day;

    /** @var  int */
    protected $uniques7Day;

    /** @var  int */
    protected $count14Day;

    /** @var  int */
    protected $count7Day;

    /** @var  int */
    protected $count30Day;

    /** @var  int */
    protected $uniques60Day;

    /** @var  int */
    protected $uniques30Day;

    /** @var  int */
    protected $count60Day;

    /** @var  int */
    protected $count1Day;

    /** @var  int */
    protected $countLifetime;

    /** @var  int */
    protected $uniquesLifetime;

    /** @var  \DateTime */
    protected $createTime;

    /** @var  \DateTime */
    protected $updateTime;

    /**
     * @return int
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param int $sid
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTraitType()
    {
        return $this->traitType;
    }

    /**
     * @param string $traitType
     */
    public function setTraitType($traitType)
    {
        $this->traitType = $traitType;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCrUid()
    {
        return $this->crUid;
    }

    /**
     * @param int $crUid
     */
    public function setCrUid($crUid)
    {
        $this->crUid = $crUid;
    }

    /**
     * @return int
     */
    public function getUpUid()
    {
        return $this->upUid;
    }

    /**
     * @param int $upUid
     */
    public function setUpUid($upUid)
    {
        $this->upUid = $upUid;
    }

    /**
     * @return string
     */
    public function getIntegrationCode()
    {
        return $this->integrationCode;
    }

    /**
     * @param string $integrationCode
     */
    public function setIntegrationCode($integrationCode)
    {
        $this->integrationCode = $integrationCode;
    }

    /**
     * @return int
     */
    public function getDataSourceId()
    {
        return $this->dataSourceId;
    }

    /**
     * @param int $dataSourceId
     */
    public function setDataSourceId($dataSourceId)
    {
        $this->dataSourceId = $dataSourceId;
    }

    /**
     * @return int
     */
    public function getFolderId()
    {
        return $this->folderId;
    }

    /**
     * @param int $folderId
     */
    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
    }

    /**
     * @return int
     */
    public function getCount90Day()
    {
        return $this->count90Day;
    }

    /**
     * @param int $count90Day
     */
    public function setCount90Day($count90Day)
    {
        $this->count90Day = $count90Day;
    }

    /**
     * @return int
     */
    public function getUniques90Day()
    {
        return $this->uniques90Day;
    }

    /**
     * @param int $uniques90Day
     */
    public function setUniques90Day($uniques90Day)
    {
        $this->uniques90Day = $uniques90Day;
    }

    /**
     * @return int
     */
    public function getUniques1Day()
    {
        return $this->uniques1Day;
    }

    /**
     * @param int $uniques1Day
     */
    public function setUniques1Day($uniques1Day)
    {
        $this->uniques1Day = $uniques1Day;
    }

    /**
     * @return int
     */
    public function getUniques14Day()
    {
        return $this->uniques14Day;
    }

    /**
     * @param int $uniques14Day
     */
    public function setUniques14Day($uniques14Day)
    {
        $this->uniques14Day = $uniques14Day;
    }

    /**
     * @return int
     */
    public function getUniques7Day()
    {
        return $this->uniques7Day;
    }

    /**
     * @param int $uniques7Day
     */
    public function setUniques7Day($uniques7Day)
    {
        $this->uniques7Day = $uniques7Day;
    }

    /**
     * @return int
     */
    public function getCount14Day()
    {
        return $this->count14Day;
    }

    /**
     * @param int $count14Day
     */
    public function setCount14Day($count14Day)
    {
        $this->count14Day = $count14Day;
    }

    /**
     * @return int
     */
    public function getCount7Day()
    {
        return $this->count7Day;
    }

    /**
     * @param int $count7Day
     */
    public function setCount7Day($count7Day)
    {
        $this->count7Day = $count7Day;
    }

    /**
     * @return int
     */
    public function getCount30Day()
    {
        return $this->count30Day;
    }

    /**
     * @param int $count30Day
     */
    public function setCount30Day($count30Day)
    {
        $this->count30Day = $count30Day;
    }

    /**
     * @return int
     */
    public function getUniques60Day()
    {
        return $this->uniques60Day;
    }

    /**
     * @param int $uniques60Day
     */
    public function setUniques60Day($uniques60Day)
    {
        $this->uniques60Day = $uniques60Day;
    }

    /**
     * @return int
     */
    public function getUniques30Day()
    {
        return $this->uniques30Day;
    }

    /**
     * @param int $uniques30Day
     */
    public function setUniques30Day($uniques30Day)
    {
        $this->uniques30Day = $uniques30Day;
    }

    /**
     * @return int
     */
    public function getCount60Day()
    {
        return $this->count60Day;
    }

    /**
     * @param int $count60Day
     */
    public function setCount60Day($count60Day)
    {
        $this->count60Day = $count60Day;
    }

    /**
     * @return int
     */
    public function getCount1Day()
    {
        return $this->count1Day;
    }

    /**
     * @param int $count1Day
     */
    public function setCount1Day($count1Day)
    {
        $this->count1Day = $count1Day;
    }

    /**
     * @return int
     */
    public function getCountLifetime()
    {
        return $this->countLifetime;
    }

    /**
     * @param int $countLifetime
     */
    public function setCountLifetime($countLifetime)
    {
        $this->countLifetime = $countLifetime;
    }

    /**
     * @return int
     */
    public function getUniquesLifetime()
    {
        return $this->uniquesLifetime;
    }

    /**
     * @param int $uniquesLifetime
     */
    public function setUniquesLifetime($uniquesLifetime)
    {
        $this->uniquesLifetime = $uniquesLifetime;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param \DateTime $createTime
     */
    public function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param \DateTime $updateTime
     */
    public function setUpdateTime(\DateTime $updateTime)
    {
        $this->updateTime = $updateTime;
    }
}
