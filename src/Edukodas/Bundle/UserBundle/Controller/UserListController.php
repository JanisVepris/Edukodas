<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Edukodas\Bundle\UserBundle\Entity\StudentTeam;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edukodas\Bundle\UserBundle\Form\UserListFilterType;
use Symfony\Component\HttpFoundation\Request;

class UserListController extends Controller
{
    public function listAction(Request $request)
    {
        $page = $request->get('page', '1');

        $filterForm = $this->createForm(UserListFilterType::class);


        $filterForm->handleRequest($request);

        $class = $filterForm->get('class')->getData();
        $team = $filterForm->get('team')->getData();

        $statisticsService = $this->get('edukodas.statistics');

        if ($team && $class) {
            $userList = $statisticsService->getStudentListByTeamAndClass($team, $class, $page);
        } elseif ($team) {
            $userList = $statisticsService->getStudentListByTeam($team, $page);
        } elseif ($class) {
            $userList = $statisticsService->getStudentListByClass($class, $page);
        } else {
            $userList = $statisticsService->getStudentList($page);
        }

        $amountRange = $statisticsService->getMinMaxAmounts($team, $class);

        $teamList = $statisticsService->getTeamStats($team, $class);

        return $this->render('EdukodasTemplateBundle:Users:userList.html.twig', [
            'teamList' => $teamList,
            'userList' => $userList,
            'filterForm' => $filterForm->createView(),
            'maxAmount' => $amountRange['max'],
            'minAmount' => $amountRange['min'],
        ]);
    }
}
