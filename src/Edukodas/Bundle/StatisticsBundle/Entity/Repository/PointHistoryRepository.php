<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\UserBundle\Entity\User;

class PointHistoryRepository extends EntityRepository
{
    /**
     * @param User $teacher
     * @param int $maxEntries
     *
     * @return Collection|PointHistory[]
     */
    public function getRecentEntriesByTeacher(User $teacher, $maxEntries = 10)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->where('ph.teacher = :teacher')
            ->orderBy('ph.createdAt', 'DESC')
            ->setMaxResults($maxEntries)
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $result;
    }
}
