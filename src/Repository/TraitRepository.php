<?php

namespace Audiens\AdobeClient\Repository;

use Audiens\AdobeClient\Auth;
use Audiens\AdobeClient\CachableTrait;
use Audiens\AdobeClient\CacheableInterface;
use Audiens\AdobeClient\Entity\TraitMetrics;
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

    const TRAITS_TREND_URL = 'https://bank.demdex.com/portal/api/v1/reports/traits-trend';

    const SANDBOX_TREND_URL = 'https://bank-beta.demdex.com/portal/api/v1/reports/traits-trend';

    /** @var Client */
    protected $client;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    /** @var  string */
    protected $trendUrl;

    const CACHE_NAMESPACE = 'adobe_trait_repository_find_all';

    const CACHE_EXPIRATION = 3600;

    /**
     * TraitRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheEnabled = $cache instanceof Cache;
        $this->baseUrl = self::BASE_URL;
        $this->trendUrl = self::TRAITS_TREND_URL;
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
     * @return string
     */
    public function getTrendUrl()
    {
        return $this->trendUrl;
    }

    /**
     * @param string $trendUrl
     */
    public function setTrendUrl($trendUrl)
    {
        $this->trendUrl = $trendUrl;
    }



    /**
     * @param $id
     *
     * @return Traits|null
     */
    public function findOneById($id)
    {

        $compiledUrl = $this->baseUrl . $id.'?includeMetrics=true';

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
        $date = date('Y_m_d_H');

        $cacheKey = self::CACHE_NAMESPACE . sha1($date);

        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $compiledUrl = $this->baseUrl . "?includeMetrics=true";

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

        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;
    }

    /**
     * @param $sid
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $dayInterval
     * @return array
     * @throws RepositoryException
     */
    public function getTrendByTrait($sid, \DateTime $startDate, \DateTime $endDate, $dayInterval = '1D')
    {
        $cacheKey = self::CACHE_NAMESPACE . sha1($startDate->getTimestamp().$endDate->getTimestamp());

        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $bodyPost =
            [
                'startDate' => $startDate->getTimestamp() * 1000,
                'endDate' => $endDate->getTimestamp() * 1000,
                'interval' => $dayInterval,
                'sids' => [$sid],
                'usePartnerLevelOverlap' => false
            ];

        $response = $this->client->request(
            'POST',
            $this->trendUrl,
            [
                'headers' =>
                    [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => \json_encode($bodyPost)
            ]
        );

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::genericFailed($repositoryResponse);
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();


        $result = [];

        foreach ($responseContent as $traitArray) {
            if (!empty($traitArray['metrics']) && count($traitArray['metrics']) > 0) {
                $traitObj = Traits::fromArray($traitArray);

                $traitObj->setMetrics([]);

                foreach ($traitArray['metrics'] as $timestamp => $metric) {
                    $traitMetric = new TraitMetrics();

                    $time = $timestamp / 1000;
                    $dateObj = new \DateTime();
                    $dateObj->setTimestamp($time);
                    $traitMetric->setTimestamp($dateObj);
                    $traitMetric->setCount($metric['count']);
                    $traitMetric->setUniques($metric['uniques']);

                    $traitObj->addMetrics($traitMetric);
                }

                $result[] = $traitObj;
            }
        }

        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;
    }
}
