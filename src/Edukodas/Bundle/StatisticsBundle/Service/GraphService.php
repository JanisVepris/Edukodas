<?php

namespace Edukodas\Bundle\StatisticsBundle\Service;

use Edukodas\Bundle\StatisticsBundle\Entity\Repository\PointHistoryRepository;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;

class GraphService
{
    const FILTER_TIMESPAN_TODAY = 1;
    const FILTER_TIMESPAN_WEEK = 2;
    const FILTER_TIMESPAN_MONTH = 3;
    const FILTER_TIMESPAN_YEAR = 4;
    const FILTER_TIMESPAN_ALL = 5;

    /**
     * @var StatisticsService
     */
    private $statisticsService;

    /**
     * @var PointHistoryRepository
     */
    private $pointHistoryRepository;

    /**
     * GraphService constructor.
     * @param StatisticsService $statisticsService
     */
    public function __construct(StatisticsService $statisticsService, PointHistoryRepository $pointHistoryRepository)
    {
        $this->statisticsService = $statisticsService;
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    /**
     * @param int|null $timespan
     *
     * @return array
     */
    public function getTeamPieChartGraph(int $timespan = null)
    {
        $timespan = $this->getTimeSpanObj($timespan);

        $teamTotalPoints = $this->pointHistoryRepository->getTeamPointTotalSinceDate($timespan);

        $graphData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => [],
                ]
            ],
        ];

        foreach ($teamTotalPoints as $teamData) {
            $graphData['labels'][] = $teamData['title'];
            $graphData['datasets'][0]['data'][] = (int) $teamData['amount'];
            $graphData['datasets'][0]['backgroundColor'][] = $teamData['color'];
        }

        return [
            'data' => $teamTotalPoints,
            'graphData' => json_encode($graphData)
        ];
    }

    /**
     * @param int|null $timespan
     *
     * @return array
     */
    public function getTeamLineChartGraph(int $timespan = null)
    {
        $fromDate = $this->getTimeSpanObj($timespan);
        $graphData = [];

        switch ($timespan) {
            case self::FILTER_TIMESPAN_WEEK:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByDayOfTheWeek($fromDate);
                break;
            case self::FILTER_TIMESPAN_MONTH:
                // TODO: Prie entity pridet weekOfTheMonth
                //$teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByWeek($fromDate);
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByMonth($fromDate);
                break;
            case self::FILTER_TIMESPAN_YEAR:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByMonth($fromDate);
                break;
            case self::FILTER_TIMESPAN_ALL:
            default:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByMonth($fromDate);
                //$teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByYear($fromDate);
        }

        // Group team data by month
        $groupedTeamData = [];

        foreach ($teamPoints as $teamData) {
            $groupedTeamData[$teamData['month']][] = $teamData;
        }
//
//        // Count different teams
//        $teamCount = 0;
//
//        foreach ($groupedTeamData as $team) {
//            if (count($team) > $teamCount) {
//                $teamCount = count($team);
//            }
//        }
//
//        // Fill team data
//        for ($i = 0; $i < 12; $i++) {
//            for ($j = 0; $j < $teamCount; $j++) {
//                $emptyTeamData[$i][] = [
//
//                ]
//            }
//        }

        for ($i = 1; $i <= 12; $i++) {
            $dateObj = \DateTime::createFromFormat('!m', $i);
            $graphData['labels'][] = $dateObj->format('F');

            foreach ($groupedTeamData[$i] as $key => $teamData) {
                $graphData['datasets'][$key]['label'] = $teamData['title'];
                $graphData['datasets'][$key]['data'][] = (int) $teamData['amount'];
                $graphData['datasets'][$key]['borderColor'] = $teamData['color'];
                $graphData['datasets'][$key]['fill'] = false;
                $graphData['datasets'][$key]['lineTension'] = 0.1;
            }
        }

        return [
            'data' => $teamPoints,
            'graphData' => json_encode($graphData)
        ];
    }

    /**
     * @param int|null $timespan
     *
     * @return \DateTime|null
     */
    private function getTimeSpanObj(int $timespan = null)
    {
        switch ($timespan) {
            case static::FILTER_TIMESPAN_YEAR:
                return new \DateTime('-1 year');
                break;

            case static::FILTER_TIMESPAN_MONTH:
                return new \DateTime('-1 month');
                break;

            case static::FILTER_TIMESPAN_WEEK:
                return new \DateTime('-1 week');
                break;

            case static::FILTER_TIMESPAN_TODAY:
                return new \DateTime('-1 day');
                break;

            case static::FILTER_TIMESPAN_ALL:
            default:
                return null;
                break;
        }
    }
}
