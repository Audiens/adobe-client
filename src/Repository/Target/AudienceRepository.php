<?php

namespace Audiens\AdobeClient\Repository\Target;

use Audiens\AdobeClient\CachableTrait;
use Audiens\AdobeClient\CacheableInterface;
use Audiens\AdobeClient\Entity\Target\Audience;
use Audiens\AdobeClient\Exceptions\RepositoryException;
use Audiens\AdobeClient\Repository\RepositoryResponse;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

/**
 * Class AudienceRepository
 */
class AudienceRepository implements CacheableInterface
{
    use CachableTrait;

    const BASE_URL = 'https://mc.adobe.io/%s/target/audiences/';

    /** @var Client */
    protected $client;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    const CACHE_NAMESPACE = 'adobe_target_repository_find_all';

    const CACHE_EXPIRATION = 3600;

    /**
     * AudienceRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null      $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client       = $client;
        $this->cache        = $cache;
        $this->cacheEnabled = $cache instanceof Cache;
        $this->baseUrl      = self::BASE_URL;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function create(Audience $audience, $tenant)
    {
        $compiledUrl = sprintf($this->baseUrl, $tenant);

        if (empty($audience->getName())) {
            return;
        }

        $response = $this->client->request(
            'POST',
            $compiledUrl,
            [

                'body' => \json_encode($audience->toArray()),
            ]
        );

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream          = $response->getBody();
        $responseContent = \json_decode($stream->getContents(), true);
        $stream->rewind();

        return Audience::fromArray($responseContent);
    }

    public function update(Audience $audience, $tenant)
    {
        $compiledUrl = sprintf($this->baseUrl, $tenant);

        $compiledUrl = $compiledUrl. $audience->getId();

        if (empty($audience->getId())) {
            return;
        }

        $response = $this->client->request(
            'PUT',
            $compiledUrl,
            [
                'body' => \json_encode($audience->toArray()),
            ]
        );

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream          = $response->getBody();
        $responseContent = \json_decode($stream->getContents(), true);
        $stream->rewind();

        return Audience::fromArray($responseContent);
    }

    public function findOneById($id, $tenant)
    {
        if (empty($tenant)) {
            RepositoryException::genericFailed('Missing tenant params');
        }

        $baseUrl = sprintf($this->baseUrl, $tenant);

        $compiledUrl = $baseUrl . '?id=' . $id;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream          = $response->getBody();
        $responseContent = \json_decode($stream->getContents(), true);
        $stream->rewind();

        if (!isset($responseContent['audiences']) || count($responseContent['audiences']) == 0) {
            return null;
        }

        if (count($responseContent['audiences']) > 1) {
            RepositoryException::genericFailed('Adobe Target returned more that one audiences...please check your id');
        }

        return Audience::fromArray($responseContent['audiences'][0]);
    }

    public function findAll($tenant)
    {
        if (empty($tenant)) {
            RepositoryException::genericFailed('Missing tenant params');
        }

        $date = date('Y_m_d_H');

        $cacheKey = self::CACHE_NAMESPACE . sha1($date);

        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $compiledUrl = sprintf($this->baseUrl, $tenant);

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::genericFailed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = \json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!isset($responseContent['audiences']) || count($responseContent['audiences']) == 0) {
            return $result;
        }

        $audiences = $responseContent['audiences'];

        foreach ($audiences as $audience) {
            $result[] = Audience::fromArray($audience);
        }

        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;
    }
}
