<?php

namespace Edukodas\Bundle\StatisticsBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Edukodas\Bundle\StatisticsBundle\Entity\Repository\PointHistoryRepository;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;

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
}
