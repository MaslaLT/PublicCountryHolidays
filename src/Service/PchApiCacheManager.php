<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PchApiCache;

class PchApiCacheManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addToCache(string $requestUrl, array $json)
    {
        $pchApiCache = new PchApiCache();
        $pchApiCache->setRequest($requestUrl);
        $pchApiCache->setResponse($json);

        $this->entityManager->persist($pchApiCache);
        $this->entityManager->flush();
    }

    public function loadFromCache(string $requestUrl)
    {
        return $this->entityManager
            ->getRepository(PchApiCache::class)
            ->findOneBy([
                'request' => $requestUrl
            ]);
    }

    public function clearCache()
    {
        $allCache = $this->entityManager
            ->getRepository(PchApiCache::class)
            ->findAll();

        foreach ($allCache as $cacheItem) {
            $this->entityManager->remove($cacheItem);
        }

        $this->entityManager->flush();
    }

}