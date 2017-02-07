<?php

namespace Audiens\AdobeClient\Repository;

use Audiens\AdobeClient\Entity\Error;
use GuzzleHttp\Psr7\Response;

/**
 * Class TraitRepository
 */
class RepositoryResponse
{
    const STATUS_SUCCESS = 'OK';

    /** @var bool */
    protected $successful = false;

    /** @var  string */
    protected $response;

    /** @var  Error */
    protected $error;

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getResponseAsArray()
    {
        return json_decode($this->response, true);
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * @param boolean $successful
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }


    /**
     * @param Response $response
     *
     * @return RepositoryResponse
     */
    public static function fromResponse(Response $response)
    {
        $self = new self();
        $error = new Error();

        $self->setSuccessful(false);

        $responseContent = self::getResponseContent($response);
        $self->setResponse($responseContent);

        $responseArray = json_decode($responseContent, true);


        if (!isset($responseArray['error'])) {
            $self->setSuccessful(true);
        }

        if (!$self->isSuccessful()) {
            $error = Error::fromArray($responseArray);
        }

        $self->setError($error);

        return $self;
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    private static function getResponseContent(Response $response)
    {
        $responseContent = $response->getBody()->getContents();

        $response->getBody()->rewind();

        return $responseContent;
    }
}
