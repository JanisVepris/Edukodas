<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeacherProfileController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        $points = new PointHistory();

        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getRecentEntriesByTeacher($user);

        $form = $this->createForm(PointHistoryType::class, $points, ['user' => $this->getUser()]);

        return $this->render('EdukodasTemplateBundle:Profile:teacherprofile.html.twig', [
            'user' => $user,
            'pointHistory' => $pointHistory,
            'addPointsForm' => $form->createView()
        ]);
    }
}
