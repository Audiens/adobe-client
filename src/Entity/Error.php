<?php

namespace Audiens\AdobeClient\Entity;

/**
 * Class Error
 */
class Error
{

    use HydratableTrait;

    protected $error_id;

    protected $error;

    protected $error_description;

    protected $error_code;

    /**
     * @return mixed
     */
    public function getErrorId()
    {
        return $this->error_id;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getErrorDescription()
    {
        return $this->error_description;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }
}
