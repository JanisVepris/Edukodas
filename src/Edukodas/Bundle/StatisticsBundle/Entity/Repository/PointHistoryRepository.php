<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param User $student
     * @param int $maxEntries
     *
     * @return Collection|PointHistory[]
     */
    public function getRecentEntriesByStudent(User $student, $maxEntries = 10): Array
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->where('ph.student = :student')
            ->orderBy('ph.id', 'DESC')
            ->setMaxResults($maxEntries)
            ->setParameter('student', $student)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $result;
    }

    /**
     * @param User $student
     *
     * @return int
     */
    public function getTotalPointsByStudent(User $student): int
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->select('SUM(ph.amount)')
            ->where('ph.student = :student')
            ->setParameter('student', $student)
            ->getQuery()
            ->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        if (!$result) {
            return 0;
        }

        return $result;
    }
}
