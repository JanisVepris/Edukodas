<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;
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
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('ph.createdAt', 'DESC')
            ->setMaxResults($maxEntries)
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $result;
    }

    /**
     * @param User $teacher
     *
     * @return int
     */
    public function getTotalPositivePointsByTeacher(User $teacher): int
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->select('SUM(ph.amount)')
            ->where('ph.teacher = :teacher')
            ->andWhere('ph.amount > 0')
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        if (!$result) {
            return 0;
        }

        return $result;
    }

    /**
     * @param User $teacher
     *
     * @return int
     */
    public function getTotalNegativePointsByTeacher(User $teacher): int
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->select('SUM(ph.amount)')
            ->where('ph.teacher = :teacher')
            ->andWhere('ph.amount < 0')
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        if (!$result) {
            return 0;
        }

        return $result;
    }

    /**
     * @param User $student
     * @param int $maxEntries
     *
     * @return Collection|PointHistory[]
     */
    public function getRecentEntriesByStudent(User $student, $maxEntries = 10): array
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $this
            ->createQueryBuilder('ph')
            ->where('ph.student = :student')
            ->andWhere('ph.deletedAt IS NULL')
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
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('student', $student)
            ->getQuery()
            ->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        if (!$result) {
            return 0;
        }

        return $result;
    }

    /**
     * @return ArrayCollection
     */
    public function getStudentPointTotals()
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select('s.id', 'SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->where('ph.deletedAt IS NULL')
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentTeam $team
     *
     * @return ArrayCollection
     */
    public function getStudentPointTotalsByTeam(StudentTeam $team)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select('s.id', 'SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->where('s.studentTeam = :team')
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('team', $team)
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentGeneration $studentGeneration
     *
     * @return ArrayCollection
     */
    public function getStudentPointTotalsByGeneration(StudentGeneration $studentGeneration)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select('s.id', 'SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->where('s.studentGeneration = :generation')
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('generation', $studentGeneration)
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentClass $studentClass
     *
     * @return ArrayCollection
     */
    public function getStudentPointTotalsByClass(StudentClass $studentClass)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select('s.id', 'SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->where('s.studentClass = :class')
            ->andWhere('ph.deletedAt IS NULL')
            ->setParameter('class', $studentClass)
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @return ArrayCollection
     */
    public function getStudentList()
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select(
                's.id',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentTeam $studentTeam
     *
     * @return ArrayCollection
     */
    public function getStudentListByTeam(StudentTeam $studentTeam)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select(
                's.id',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('s.studentTeam = :studentTeam')
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setParameter('studentTeam', $studentTeam)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentClass $studentClass
     *
     * @return ArrayCollection
     */
    public function getStudentListByClass(StudentClass $studentClass)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select(
                's.id',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('s.studentClass = :studentClass')
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setParameter('studentClass', $studentClass)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }

    /**
     * @param StudentTeam $studentTeam
     * @param StudentClass $studentClass
     *
     * @return ArrayCollection
     */
    public function getStudentListByTeamAndClass(StudentTeam $studentTeam, StudentClass $studentClass)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('ph');

        $result = $qb
            ->select(
                's.id',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('s.studentTeam = :studentTeam')
            ->andWhere('s.studentClass = :studentClass')
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setParameter('studentTeam', $studentTeam)
            ->setParameter('studentClass', $studentClass)
            ->getQuery()
            ->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return new ArrayCollection($result);
    }
}
