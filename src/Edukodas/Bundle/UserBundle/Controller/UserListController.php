<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Edukodas\Bundle\UserBundle\Entity\StudentTeam;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserListController extends Controller
{
    public function indexAction()
    {
        $teamList = $this->getDoctrine()
            ->getRepository('EdukodasUserBundle:StudentTeam')
            ->findAll();

        $classList = $this->getDoctrine()
            ->getRepository('EdukodasUserBundle:StudentClass')
            ->findAll();

        $statisticsService = $this->get('edukodas.statistics');

        $userList = $statisticsService->getStudentList();
        $maxAmount = $userList->first()['amount'];
        $minAmount = $userList->last()['amount'];

        return $this->render('@EdukodasTemplate/Users/list.html.twig', [
            'teamList' => $teamList,
            'classList' => $classList,
            'userList' => $userList,
            'maxAmount' => $maxAmount,
            'minAmount' => $minAmount,
        ]);
    }

    public function listAction(Request $request)
    {
        $team = $this->getDoctrine()
            ->getRepository('EdukodasUserBundle:StudentTeam')
            ->find($request->request->get('teamId'));

        $class = $this->getDoctrine()
            ->getRepository('EdukodasUserBundle:StudentClass')
            ->find($request->request->get('classId'));

        $statisticsService = $this->get('edukodas.statistics');

        if ($team && $class) {
            $userList = $statisticsService->getStudentListByTeamAndClass($team, $class);
        } elseif ($team) {
            $userList = $statisticsService->getStudentListByTeam($team);
        } elseif ($class) {
            $userList = $statisticsService->getStudentListByClass($class);
        } else {
            $userList = $statisticsService->getStudentList();
        }

        $maxAmount = $userList->first()['amount'];
        $minAmount = $userList->last()['amount'];

        return $this->render('@EdukodasTemplate/Users/inc/_listUsers.html.twig', [
            'userList' => $userList,
            'maxAmount' => $maxAmount,
            'minAmount' => $minAmount,
        ]);
    }
}
