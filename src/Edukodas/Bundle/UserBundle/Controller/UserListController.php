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

        $pointHistoryRepo = $this->get('edukodas.pointhistory.repository');

        if ($team && $class) {
            $userList = $pointHistoryRepo->getStudentListByTeamAndClass($team, $class, $page);
        } elseif ($team) {
            $userList = $pointHistoryRepo->getStudentListByTeam($team, $page);
        } elseif ($class) {
            $userList = $pointHistoryRepo->getStudentListByClass($class, $page);
        } else {
            $userList = $pointHistoryRepo->getStudentList($page);
        }

        $amountRange = $pointHistoryRepo->getMinMaxAmounts($team, $class);

        $teamList = $pointHistoryRepo->getTeamStats($team, $class);

        return $this->render('EdukodasTemplateBundle:Users:userList.html.twig', [
            'teamList' => $teamList,
            'userList' => $userList,
            'filterForm' => $filterForm->createView(),
            'maxAmount' => $amountRange['max'],
            'minAmount' => $amountRange['min'],
        ]);
    }
}
