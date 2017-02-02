<?php

namespace Audiens\AdobeClient\Repository;

use Audiens\AdobeClient\Auth;
use Audiens\AdobeClient\CachableTrait;
use Audiens\AdobeClient\CacheableInterface;
use Audiens\AdobeClient\Entity\Traits;
use Audiens\AdobeClient\Exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class TraitRepository
 */
class TraitRepository implements CacheableInterface
{
    use CachableTrait;

    const BASE_URL = 'https://api.demdex.com:443/v1/traits/';

    const SANDBOX_BASE_URL = 'https://api-beta.demdex.com:443/v1/traits/';

    /** @var Client */
    protected $client;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    const CACHE_NAMESPACE = 'adobe_trait_repository_find_all';

    const CACHE_EXPIRATION = 3600;

    /**
     * TraitRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null      $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheEnabled = $cache instanceof Cache;
        $this->baseUrl = self::BASE_URL;
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

    /**
     * @param Traits $trait
     *
     * @return RepositoryResponse
     * @throws RepositoryException
     */
    public function add(Traits $trait)
    {

        $payload = [
            $trait->toArray(),
        ];


        $response = $this->client->request('POST', $this->baseUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {
            $stream = $response->getBody();
            $responseContent = json_decode($stream->getContents(), true);
            $stream->rewind();

            if (!(isset($responseContent['sid']))) {
                throw RepositoryException::wrongFormat(serialize($responseContent));
            }

            $trait->setSid($responseContent['sid']);
        }
        return $repositoryResponse;
    }

    /**
     * @param $id
     *
     * @return Traits|null
     */
    public function findOneById($id)
    {

        $compiledUrl = $this->baseUrl.$id;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        return Traits::fromArray($responseContent);
    }

    public function findAll()
    {
//        $cacheKey = self::CACHE_NAMESPACE.sha1($memberId.$start.$maxResults);
//
//        if ($this->isCacheEnabled()) {
//            if ($this->cache->contains($cacheKey)) {
//                return $this->cache->fetch($cacheKey);
//            }
//        }

        $compiledUrl = $this->baseUrl."?includeMetrics=true";

        $response = $this->client->request('GET', $compiledUrl);


        $repositoryResponse = RepositoryResponse::fromResponse($response);


        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::genericFailed($repositoryResponse);
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        foreach ($responseContent as $traitArray) {
            $result[] = Traits::fromArray($traitArray);
        }
//
//        if ($this->isCacheEnabled()) {
//            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
//        }

        return $result;
    }
}
