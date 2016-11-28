<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserListController extends Controller
{
    public function indexAction()
    {
        return $this->render('@EdukodasTemplate/Users/list.html.twig', [
//            'teamList' => $teamList,
//            'classList' => $classList,
        ]);
    }

    public function listAction(Request $request)
    {
        $team = $request->request->get('team');
        $class = $request->request->get('class');

        $users = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getStudentPointTotals();

        return $this->render('@EdukodasTemplate/Users/inc/_listUsers.html.twig', [
            'users' => $users,
        ]);
    }
}
