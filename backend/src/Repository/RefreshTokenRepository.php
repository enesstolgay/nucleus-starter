<?php

namespace App\Repository;

use App\Entity\RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    public function findOneByRefreshToken(string $refreshToken): ?RefreshToken
    {
        return $this->findOneBy(['refreshToken' => $refreshToken]);
    }

    public function findInvalid(?\DateTimeInterface $datetime = null): \Traversable|array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.valid < :date')
            ->setParameter('date', $datetime ?? new \DateTime(), 'datetime');

        return $qb->getQuery()->getResult();
    }

    public function findInvalidBatch(?\DateTimeInterface $datetime = null, ?int $batchSize = null, int $offset = 0): \Traversable|array
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.valid < :date')
            ->setParameter('date', $datetime ?? new \DateTime(), 'datetime')
            ->setFirstResult($offset);

        if (null !== $batchSize) {
            $qb->setMaxResults($batchSize);
        }

        return $qb->getQuery()->getResult();
    }
}
