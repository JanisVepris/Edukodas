<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentProfileController extends Controller
{
    public function indexAction($id)
    {
        if ($id === null) {
            $user = $this->getUser();
        } else {
            $user = $this->getDoctrine()->getRepository('EdukodasUserBundle:User')->find($id);
        }

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->findBy(['student' => $user]);

        $teacherPointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->findBy(['student' => $user, 'teacher' => $this->getUser()]);

        $studentPoints = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->createQueryBuilder('sp')
            ->select('SUM(sp.amount)')
            ->where('sp.student = ' . $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $points = new PointHistory();
        $pointsForm = $this->createForm(PointHistoryType::class, $points, ['user' => $user]);

        return $this->render('EdukodasTemplateBundle:Profile:studentProfile.html.twig', [
            'user' => $user,
            'teacher' => $this->getUser(),
            'points' => $studentPoints,
            'pointHistory' => $pointHistory,
            'teacherPointHistory' => $teacherPointHistory,
            'addPointsForm' => $pointsForm->createView()
        ]);
    }
}
