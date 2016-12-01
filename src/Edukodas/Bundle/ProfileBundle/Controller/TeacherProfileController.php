<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherProfileController extends Controller
{
    /**
     * @param $id
     * @return Response
     */
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

        if (!in_array('ROLE_TEACHER', $user->getRoles())) {
            throw new HttpException(400, 'User is not teachers');
        }

        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getRecentEntriesByTeacher($user);

        $pointsTotalPositive = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getTotalPositivePointsByTeacher($user);

        $pointsTotalNegative = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->getTotalNegativePointsByTeacher($user);

        $form = $this->createForm(PointHistoryType::class, new PointHistory(), ['user' => $this->getUser()]);

        return $this->render('@EdukodasTemplate/Profile/teacherProfile.html.twig', [
            'user' => $user,
            'pointsTotalPositive' => $pointsTotalPositive,
            'pointsTotalNegative' => $pointsTotalNegative,
            'pointHistory' => $pointHistory,
            'addPointsForm' => $form->createView()
        ]);
    }
}
