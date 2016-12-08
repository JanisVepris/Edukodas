<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;
use Edukodas\Bundle\UserBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;

class PointHistoryRepository extends EntityRepository
{
    /**
     * @var integer
     */
    private $pageSize;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

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
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentList(int $page = 1)
    {
        $qb = $this->createQueryBuilder('ph');

        $query = $qb
            ->select(
                's.id',
                's.fullName',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount',
                's.picture picture'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->getQuery();

        return $this->getPagination($query, $page);
    }

    /**
     * @param StudentTeam $studentTeam
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByTeam(StudentTeam $studentTeam, int $page = 1)
    {
        $qb = $this->createQueryBuilder('ph');

        $query = $qb
            ->select(
                's.id',
                's.fullName',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount',
                's.picture picture'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('s.studentTeam = :studentTeam')
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setParameter('studentTeam', $studentTeam)
            ->getQuery();

        return $this->getPagination($query, $page);
    }

    /**
     * @param StudentClass $studentClass
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByClass(StudentClass $studentClass, int $page = 1)
    {
        $qb = $this->createQueryBuilder('ph');

        $query = $qb
            ->select(
                's.id',
                's.fullName',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount',
                's.picture picture'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('s.studentClass = :studentClass')
            ->andWhere('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setParameter('studentClass', $studentClass)
            ->getQuery();

        return $this->getPagination($query, $page);
    }

    /**
     * @param StudentTeam $studentTeam
     * @param StudentClass $studentClass
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByTeamAndClass(StudentTeam $studentTeam, StudentClass $studentClass, int $page = 1)
    {
        $qb = $this->createQueryBuilder('ph');

        $query = $qb
            ->select(
                's.id',
                's.fullName',
                's.firstName',
                's.lastName',
                'st.title teamTitle',
                'st.color teamColor',
                'sc.title classTitle',
                'SUM(ph.amount) amount',
                's.picture picture'
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
            ->getQuery();

        return $this->getPagination($query, $page);
    }

    /**
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return int
     */
    public function findMaxPointAmountByClassAndTeam(StudentTeam $team = null, StudentClass $class = null): int
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this
            ->createQueryBuilder('ph')
            ->select('SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('ph.deletedAt IS NULL')
            ->orderBy('amount', 'desc')
            ->groupBy('s.id')
            ->setMaxResults(1);

        if ($team) {
            $qb->andWhere('s.studentTeam = :studentTeam')->setParameter('studentTeam', $team);
        }

        if ($class) {
            $qb->andWhere('s.studentClass = :studentClass')->setParameter('studentClass', $class);
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $result;
    }

    /**
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return int
     */
    public function findMinPointAmountByClassAndTeam(StudentTeam $team = null, StudentClass $class = null): int
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this
            ->createQueryBuilder('ph')
            ->select('SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->join('s.studentTeam', 'st')
            ->join('s.studentClass', 'sc')
            ->where('ph.deletedAt IS NULL')
            ->orderBy('amount', 'asc')
            ->groupBy('s.id')
            ->setMaxResults(1);

        if ($team) {
            $qb->andWhere('s.studentTeam = :studentTeam')->setParameter('studentTeam', $team);
        }

        if ($class) {
            $qb->andWhere('s.studentClass = :studentClass')->setParameter('studentClass', $class);
        }

        $result = $qb->getQuery()->getSingleScalarResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $result;
    }

    /**
     * @param \DateTime|null $fromDate
     * @param StudentTeam $team
     * @param StudentClass $class
     *
     * @return array
     */
    public function getTeamPointTotalSinceDate(
        \DateTime $fromDate = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $qb = $this
            ->createQueryBuilder('ph')
            ->select('t.id', 't.title', 't.color', 'SUM(ph.amount) amount')
            ->join('ph.student', 's')
            ->join('s.studentTeam', 't')
            ->groupBy('t.id')
            ->orderBy('amount', 'desc');

        if ($fromDate) {
            $qb
                ->where('ph.createdAt >= :dateTime')
                ->setParameter('dateTime', $fromDate);
        }

        if ($team) {
            $qb
                ->andWhere('s.studentTeam = :team')
                ->setParameter('team', $team);
        }

        if ($class) {
            $qb
                ->andWhere('s.studentClass = :class')
                ->setParameter('class', $class);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string         $dateFormat
     * @param \DateTime|null $fromDate
     * @param StudentTeam    $team
     * @param StudentClass   $class
     *
     * @return array
     */
    public function getTeamPointTotalGroupedByTimespan(
        string $dateFormat,
        \DateTime $fromDate = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $qb = $this
            ->createQueryBuilder('ph')
            ->select(
                't.id',
                't.title',
                't.color',
                'SUM(ph.amount) amount,
                DATE_FORMAT(ph.createdAt, \''. $dateFormat .'\') date'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 't')
            ->addGroupBy('date')
            ->addGroupBy('t.id')
            ->orderBy('ph.createdAt', 'asc');

        if ($fromDate) {
            $qb
                ->where('ph.createdAt >= :dateTime')
                ->setParameter('dateTime', $fromDate);
        }

        if ($team) {
            $qb
                ->andWhere('s.studentTeam = :team')
                ->setParameter('team', $team);
        }

        if ($class) {
            $qb
                ->andWhere('s.studentClass = :class')
                ->setParameter('class', $class);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $quantity
     * @param \DateTime|null $fromDate
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array
     */
    public function getTopUsers(
        int $quantity,
        \DateTime $fromDate = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $qb = $this
            ->createQueryBuilder('ph')
            ->select(
                's.id',
                's.fullName',
                't.color',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 't')
            ->groupBy('s.id')
            ->orderBy('amount', 'desc')
            ->setMaxResults($quantity);

        if ($fromDate) {
            $qb
                ->where('ph.createdAt >= :dateTime')
                ->setParameter('dateTime', $fromDate);
        }

        if ($team) {
            $qb
                ->andWhere('s.studentTeam = :team')
                ->setParameter('team', $team);
        }

        if ($class) {
            $qb
                ->andWhere('s.studentClass = :class')
                ->setParameter('class', $class);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Query $query
     * @param int $page
     *
     * @return PaginatorInterface
     */
    private function getPagination(Query $query, int $page)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $result = $query->getResult();

        $query->setHint('knp_paginator.count', sizeof($result));

        $pagination = $this->paginator->paginate(
            $result,
            $page,
            $this->pageSize
        );

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $pagination;
    }

    /**
     * @param int $amount
     *
     * @return int
     */
    public function getStudentRanking(int $amount): int
    {
        $allStudentPointAmounts = $this->getStudentPointTotals();

        $higherRankingStudents = $this->countHigherRankingEntries($allStudentPointAmounts, $amount);

        return $higherRankingStudents + 1;
    }

    /**
     * @param StudentTeam $team
     * @param int $amount
     *
     * @return int
     */
    public function getStudentRankingByTeam(StudentTeam $team, int $amount): int
    {
        $allStudentPointAmountInTeam = $this
            ->getStudentPointTotalsByTeam($team);

        $higherRankingStudents = $this->countHigherRankingEntries($allStudentPointAmountInTeam, $amount);

        return $higherRankingStudents + 1;
    }

    /**
     * @param StudentGeneration $studentGeneration
     * @param int $amount
     *
     * @return int
     */
    public function getStudentRankingByGeneration(StudentGeneration $studentGeneration, int $amount): int
    {
        $allStudentPointAmountInTeam = $this
            ->getStudentPointTotalsByGeneration($studentGeneration);

        $higherRankingStudents = $this->countHigherRankingEntries($allStudentPointAmountInTeam, $amount);

        return $higherRankingStudents + 1;
    }

    /**
     * @param StudentClass $studentClass
     * @param int $amount
     *
     * @return int
     */
    public function getStudentRankingByClass(StudentClass $studentClass, int $amount): int
    {
        $allStudentPointAmountInTeam = $this
            ->getStudentPointTotalsByClass($studentClass);

        $higherRankingStudents = $this->countHigherRankingEntries($allStudentPointAmountInTeam, $amount);

        return $higherRankingStudents + 1;
    }

    /**
     * @param ArrayCollection $entries
     * @param int $amount
     *
     * @return int
     */
    private function countHigherRankingEntries(ArrayCollection $entries, int $amount)
    {
        $higherRankingEntries = $entries->filter(function ($entry) use ($amount) {
            return $entry['amount'] > $amount;
        });

        return count($higherRankingEntries);
    }

    /**
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array
     */
    public function getMinMaxAmounts(StudentTeam $team = null, StudentClass $class = null)
    {
        return [
            'min' => $this->findMinPointAmountByClassAndTeam($team, $class),
            'max' => $this->findMaxPointAmountByClassAndTeam($team, $class),
        ];
    }

    /**
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array
     */
    public function getTeamStats(StudentTeam $team = null, StudentClass $class = null)
    {
        $qb = $this
            ->createQueryBuilder('ph')
            ->select(
                't.id',
                't.title',
                't.color',
                'SUM(ph.amount) amount'
            )
            ->join('ph.student', 's')
            ->join('s.studentTeam', 't')
            ->groupBy('t.id')
            ->orderBy('amount', 'desc');

        if ($team) {
            $qb
                ->andWhere('s.studentTeam = :team')
                ->setParameter('team', $team);
        }

        if ($class) {
            $qb
                ->andWhere('s.studentClass = :class')
                ->setParameter('class', $class);
        }

        return $qb->getQuery()->getResult();
    }
}
