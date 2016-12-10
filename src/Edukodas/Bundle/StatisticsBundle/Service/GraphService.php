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
     * @var PointHistoryRepository
     */
    private $pointHistoryRepository;

    /**
     * GraphService constructor.
     *
     * @param PointHistoryRepository $pointHistoryRepository
     */
    public function __construct(PointHistoryRepository $pointHistoryRepository)
    {
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    /**
     * @param int|null $timespan
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array
     */
    public function getTeamPieAndBarChartGraph(
        int $timespan = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $timespan = $this->getTimeSpanObj($timespan);

        $teamTotalPoints = $this->pointHistoryRepository->getTeamPointTotalSinceDate($timespan, $team, $class);

        $graphData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => [],
                ]
            ],
        ];

        $pieData = [];

        foreach ($teamTotalPoints as $teamData) {
            $graphData['labels'][] = $teamData['title'];
            $graphData['datasets'][0]['data'][] = (int) $teamData['amount'];
            $graphData['datasets'][0]['backgroundColor'][] = $teamData['color'];

            $pieData['datasets'][0]['data'][] = (int) max(0, $teamData['amount']);
        }

        $pieData = array_replace_recursive($graphData, $pieData);

        if (count($graphData['datasets'][0]['data']) < 2) {
            return null;
        }

        return [
            'pieData' => json_encode($pieData),
            'barData' => json_encode($graphData)
        ];
    }

    /**
     * @param int|null $timespan
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array|null
     */
    public function getTeamLineChartGraph(int $timespan = null, StudentTeam $team = null, StudentClass $class = null)
    {
        $fromDate = $this->getTimeSpanObj($timespan);

        switch ($timespan) {
            case self::FILTER_TIMESPAN_WEEK:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByTimespan(
                    '%w',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_MONTH:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByTimespan(
                    '%U',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_YEAR:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByTimespan(
                    '%m',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_TODAY:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByTimespan(
                    '%H',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_ALL:
            default:
                $teamPoints = $this->pointHistoryRepository->getTeamPointTotalGroupedByTimespan(
                    '%m-%Y',
                    $fromDate,
                    $team,
                    $class
                );
        }

        $graphData = $this->formatChartDataStructure($teamPoints);

        if (count($graphData['datasets']) < 1) {
            return null;
        }

        return json_encode($graphData);
    }

    /**
     * @param int|null $timespan
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return array|null
     */
    public function getTeamLineCumulativeChartGraph(
        int $timespan = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $fromDate = $this->getTimeSpanObj($timespan);

        switch ($timespan) {
            case self::FILTER_TIMESPAN_WEEK:
                $teamPoints = $this->pointHistoryRepository->getTeamPointSumGroupedByTimespan(
                    '%w',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_MONTH:
                $teamPoints = $this->pointHistoryRepository->getTeamPointSumGroupedByTimespan(
                    '%U',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_YEAR:
                $teamPoints = $this->pointHistoryRepository->getTeamPointSumGroupedByTimespan(
                    '%m',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_TODAY:
                $teamPoints = $this->pointHistoryRepository->getTeamPointSumGroupedByTimespan(
                    '%H',
                    $fromDate,
                    $team,
                    $class
                );
                break;
            case self::FILTER_TIMESPAN_ALL:
            default:
                $teamPoints = $this->pointHistoryRepository->getTeamPointSumGroupedByTimespan(
                    '%m-%Y',
                    $fromDate,
                    $team,
                    $class
                );
        }

        $pointsBefore = $this->pointHistoryRepository->getTeamPointSumBeforeTime($fromDate, $team, $class);
        $graphData = $this->formatChartDataStructure($teamPoints);

        foreach ($graphData['datasets'] as $teamIndex => &$team) {
            foreach ($team['data'] as $key => &$data) {
                if ($key > 0) {
                    $data += $team['data'][$key - 1];
                } elseif ($fromDate) {
                    $data += (int) $pointsBefore[$teamIndex]['amount'];
                }
            }
        }

        if (count($graphData['datasets']) < 1) {
            return null;
        }

        return json_encode($graphData);
    }

    /**
     * @param int $quantity
     * @param int|null $timespan
     * @param StudentTeam|null $team
     * @param StudentClass|null $class
     *
     * @return null|string
     */
    public function getTopUsersBarChartGraph(
        int $quantity,
        int $timespan = null,
        StudentTeam $team = null,
        StudentClass $class = null
    ) {
        $timespan = $this->getTimeSpanObj($timespan);

        $topUsers = $this->pointHistoryRepository->getTopUsers($quantity, $timespan, $team, $class);

        $graphData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => [],
                ]
            ],
        ];

        foreach ($topUsers as $user) {
            $graphData['labels'][] = $user['fullName'];
            $graphData['datasets'][0]['data'][] = (int) $user['amount'];
            $graphData['datasets'][0]['backgroundColor'][] = $user['color'];
        }

        if (count($graphData['datasets'][0]['data']) < 1) {
            return null;
        }

        return json_encode($graphData);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function formatChartDataStructure(array $data)
    {
        $graphData = [
            'labels' => array_values(array_unique(array_column($data, 'date'))),
            'datasets' => [],
        ];

        $teamNames = array_unique(array_column($data, 'title'));

        foreach ($teamNames as $teamName) {
            $dataset = [
                'label' => $teamName,
                'fill' => false,
                'lineTension' => 0.3,
            ];

            $teamData = array_filter($data, function ($value) use ($teamName) {
                return ($value['title'] === $teamName);
            });

            $teamData = array_values($teamData);

            // Find differences between all dates and team dates
            $teamDateDifferences = array_diff($graphData['labels'], array_column($teamData, 'date'));

            // Fill missing data
            foreach ($teamDateDifferences as $date) {
                $teamData[] = array_replace($teamData[0], [
                    'amount' => 0,
                    'date' => $date
                ]);
            }

            // Sort teamData by date
            usort($teamData, function ($a, $b) {
                return $a['date'] <=> $b['date'];
            });

            $dataset['data'] = array_map(function ($teamDataEntry) {
                return $teamDataEntry['amount'];
            }, $teamData);

            $dataset['borderColor'] = $teamData[0]['color'];

            $graphData['datasets'][] = $dataset;
        }

        return $graphData;
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
