<?php

namespace Audiens\AdobeClient;

/**
 * Class CachableTrait
 */
trait CachableTrait
{

    /** @var bool */
    protected $cacheEnabled;

    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    public function enableCache()
    {
        $this->cacheEnabled = true;
    }
}
