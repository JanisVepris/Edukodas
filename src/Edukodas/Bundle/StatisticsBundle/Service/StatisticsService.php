<?php

namespace Edukodas\Bundle\StatisticsBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Edukodas\Bundle\StatisticsBundle\Entity\Repository\PointHistoryRepository;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;
use Knp\Component\Pager\PaginatorInterface;

class StatisticsService
{
    /**
     * @var PointHistoryRepository
     */
    private $pointHistoryRepository;

    public function __construct(PointHistoryRepository $pointHistoryRepository)
    {
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    /**
     * @param int $amount
     *
     * @return int
     */
    public function getStudentRanking(int $amount): int
    {
        $allStudentPointAmounts = $this->pointHistoryRepository->getStudentPointTotals();

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
            ->pointHistoryRepository
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
            ->pointHistoryRepository
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
            ->pointHistoryRepository
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
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentList(int $page)
    {
        $allStudentList = $this
            ->pointHistoryRepository
            ->getStudentList($page);

        return $allStudentList;
    }

    /**
     * @param StudentTeam $studentTeam
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByTeam(StudentTeam $studentTeam, int $page)
    {
        $studentListByTeam = $this
            ->pointHistoryRepository
            ->getStudentListByTeam($studentTeam, $page);

        return $studentListByTeam;
    }

    /**
     * @param StudentClass $studentClass
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByClass(StudentClass $studentClass, int $page)
    {
        $studentListByClass = $this
            ->pointHistoryRepository
            ->getStudentListByClass($studentClass, $page);

        return $studentListByClass;
    }

    /**
     * @param StudentTeam $studentTeam
     * @param StudentClass $studentClass
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getStudentListByTeamAndClass(StudentTeam $studentTeam, StudentClass $studentClass, int $page)
    {
        $studentListByTeamAndClass = $this
            ->pointHistoryRepository
            ->getStudentListByTeamAndClass($studentTeam, $studentClass, $page);

        return $studentListByTeamAndClass;
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
            'min' => $this->pointHistoryRepository->findMinPointAmountByClassAndTeam($team, $class),
            'max' => $this->pointHistoryRepository->findMaxPointAmountByClassAndTeam($team, $class),
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
            ->pointHistoryRepository
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

        return $qb->getQuery()->getResult();
    }
}
