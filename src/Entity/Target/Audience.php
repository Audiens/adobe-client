<?php

namespace Audiens\AdobeClient\Entity\Target;

use Audiens\AdobeClient\Entity\HydratableTrait;

/**
 * Class Audience
 */
class Audience
{
    const TARGET_RULE_AND = 'AND';
    const TARGET_RULE_OR  = 'OR';

    use HydratableTrait;

    /** @var  int */
    protected $id;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $description;

    /** @var \DateTime */
    protected $modifiedAt;

    /** @var string */
    protected $targetRuleLogicalCondition;

    /** @var array */
    protected $targetRuleConditions = [];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return string
     */
    public function getTargetRuleLogicalCondition()
    {
        return $this->targetRuleLogicalCondition;
    }

    /**
     * @param string $targetRuleLogicalCondition
     */
    public function setTargetRuleLogicalCondition($targetRuleLogicalCondition)
    {
        $this->targetRuleLogicalCondition = $targetRuleLogicalCondition;
    }

    /**
     * @return array
     */
    public function getTargetRuleConditions()
    {
        return $this->targetRuleConditions;
    }

    /**
     * @param array $targetRuleConditions
     */
    public function setTargetRuleConditions($targetRuleConditions)
    {
        $this->targetRuleConditions = $targetRuleConditions;
    }

    public function toArray()
    {
        $returnArray = [
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
        ];

        if (!empty($this->targetRuleLogicalCondition)) {
            $returnArray['targetRule'] = [$this->targetRuleLogicalCondition => $this->getTargetRuleConditions()];
        }

        return $returnArray;
    }

    /**
     * @override
     */
    public static function fromArray(array $objectArray)
    {
        $object = new self();
        self::getHydrator()->hydrate($objectArray, $object);

        if (isset($objectArray['targetRule']) && count($objectArray['targetRule']) > 0) {
            foreach ($objectArray['targetRule'] as $logical => $rule) {
                $object->targetRuleLogicalCondition = $logical;
                $object->targetRuleConditions       = $rule;
            }
        }

        return $object;
    }
}
