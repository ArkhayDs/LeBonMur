<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Annonce[]
     */
    public function findAllPublished(): array
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.createdAt is NOT NULL")
            ->orderBy('a.createdAt','DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Annonce[]
     */
    public function findAllPublishedById($id): array
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.id =:value")
            ->setParameter("value",$id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findAllAndJoin(): array
    {
        return $this->createQueryBuilder("a")
            ->leftJoin("a.questions","q")
            ->leftJoin("q.reponses","r")
            ->innerJoin("a.author","u")
            ->leftJoin('a.categories','c')
            ->addSelect("q")
            ->addSelect("r")
            ->addSelect("u")
            ->addSelect("c")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function findByIdAndJoin($id): array
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.id =:value")
            ->setParameter("value",$id)
            ->leftJoin("a.questions","q")
            ->leftJoin("q.reponses","r")
            ->leftJoin('a.categories','c')
            ->addSelect("q")
            ->addSelect("r")
            ->addSelect("c")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $value
     * @return array
     */
    public function findAllFromTag($value): array
    {
        return $this->createQueryBuilder("a")
            ->leftJoin('a.categories','c')
            ->andWhere('c.name =:value')
            ->setParameter("value",$value)
            ->addSelect('c')
            ->getQuery()
            ->getResult();
    }
}
