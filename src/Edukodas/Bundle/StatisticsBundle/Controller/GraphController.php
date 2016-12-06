<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Form\GraphType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GraphController extends Controller
{
    public function indexAction(Request $request)
    {
        $filterForm = $this->createForm(GraphType::class);
        $filterForm->handleRequest($request);

        $graphService = $this->get('edukodas.graph');

        $timespan = $filterForm->get('timespan')->getData();
        $team = $filterForm->get('team')->getData();
        $class = $filterForm->get('class')->getData();

        $teamPieGraph = $graphService->getTeamPieChartGraph($timespan, $team, $class);
        $teamLineGraph = $graphService->getTeamLineChartGraph($timespan, $team, $class);

        return $this->render('@EdukodasTemplate/Graph/graph.html.twig', [
            'filterForm' => $filterForm->createView(),
            'teamGraph' => $teamPieGraph,
            'teamLineGraph' => $teamLineGraph,
        ]);
    }
}
