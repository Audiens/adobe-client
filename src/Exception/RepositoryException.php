<?php

namespace Audiens\AdobeClient\Exceptions;

use Audiens\AdobeClient\Entity\Traits;

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

    /**
     * @param $responseContent
     *
     * @return self
     */
    public static function wrongFormat($responseContent)
    {
        return new self($responseContent);
    }


    /**
     * @param Traits $trait
     *
     * @return self
     */
    public static function missingSid($trait)
    {
        return new self('Missing sid for '.serialize($trait->getName()));
    }
}
