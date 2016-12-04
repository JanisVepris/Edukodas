<?php

namespace Edukodas\Bundle\StatisticsBundle\Service;

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
     * GraphService constructor.
     * @param StatisticsService $statisticsService
     */
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * @param int|null $timespan
     *
     * @return array
     */
    public function getTeamPieChartGraph(int $timespan = null)
    {
        $timespan = $this->getTimeSpanObj($timespan);

        $teamTotalPoints = $this->statisticsService->getTeamPointTotals($timespan);

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
     * @param int $timespan
     */
    public function getTeamLineChartGraph(int $timespan)
    {
        $dateFrom = $this->getTimeSpanObj($timespan);

        // TODO: Pabaigt
        switch ($timespan) {
            case self::FILTER_TIMESPAN_YEAR:
                $teamPoints = $this->statisticsService->getTeamPointTotalsByMonth($dateFrom);
                break;
            case self::FILTER_TIMESPAN_WEEK:
                $teamPoints = $this->statisticsService->getTeamPointTotalsByWeek($dateFrom);
                break;
            default:
                $teamPoints = $this->statisticsService->getTeamPointTotalsByYear($dateFrom);
        }


        dump($teamPoints);
        die;

        // TODO: Paruost duomenis grafikam
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
