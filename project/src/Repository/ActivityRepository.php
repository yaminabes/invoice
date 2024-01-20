<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function add(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find activities between given start and end dates for a specific responsible user.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param User|null $responsable
     * @return Activity[]
     */
    public function findActivitiesBetweenDates(\DateTime $startDate, \DateTime $endDate, ?User $responsable): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        if ($responsable instanceof User) {
            $queryBuilder
                ->join('a.user', 'u')
                ->andWhere('u.responsable = :responsable')
                ->setParameter('responsable', $responsable);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find activities of developpeurs under the responsibility of the given responsable.
     *
     * @param User $responsable
     * @return Activity[]
     */
    public function findActivitiesByResponsable(User $responsable): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->andWhere('u.responsable = :responsable')
            ->setParameter('responsable', $responsable)
            ->getQuery()
            ->getResult();
    }
}
