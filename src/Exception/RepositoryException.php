<?php

namespace Audiens\AdobeClient\Exceptions;

/**
 * Class RepositoryException
 */
class RepositoryException extends \Exception
{
    /**
     * @param string $reason
     *
     * @return self
     */
    public static function genericFailed($reason)
    {
        return new self($reason);
    }
}
