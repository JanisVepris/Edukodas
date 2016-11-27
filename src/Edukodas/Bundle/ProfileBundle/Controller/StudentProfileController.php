<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        if (!in_array('ROLE_STUDENT', $user->getRoles())) {
            throw new HttpException(400, 'User is not student');
        }

        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getRecentEntriesByStudent($user);

        $studentPoints = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getTotalPointsByStudent($user);

        $points = new PointHistory();
        $pointsForm = $this->createForm(PointHistoryType::class, $points, ['user' => $user]);

        return $this->render('EdukodasTemplateBundle:Profile:studentProfile.html.twig', [
            'user' => $user,
            'teacher' => $this->getUser(),
            'points' => $studentPoints,
            'pointHistory' => $pointHistory,
            'addPointsForm' => $pointsForm->createView()
        ]);
    }
}
