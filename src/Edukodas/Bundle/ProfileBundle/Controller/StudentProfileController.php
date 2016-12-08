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
            throw new HttpException(403, 'User is not student');
        }

        $pointHistory = $this
            ->get('edukodas.pointhistory.repository')
            ->getRecentEntriesByStudent($user);

        $studentPoints = $this
            ->get('edukodas.pointhistory.repository')
            ->getTotalPointsByStudent($user);

        $statisticsService = $this->get('edukodas.pointhistory.repository');

        $points = new PointHistory();
        $pointsForm = $this->createForm(PointHistoryType::class, $points, ['user' => $this->getUser()]);

        $rankingTotal = $statisticsService->getStudentRanking($studentPoints);
        $rankingByTeam = $statisticsService->getStudentRankingByTeam($user->getStudentTeam(), $studentPoints);
        $rankingByGeneration = $statisticsService->getStudentRankingByGeneration(
            $user->getStudentGeneration(),
            $studentPoints
        );
        $rankingByClass = $statisticsService->getStudentRankingByClass(
            $user->getStudentClass(),
            $studentPoints
        );

        return $this->render('EdukodasTemplateBundle:Profile:studentProfile.html.twig', [
            'user' => $user,
            'teacher' => $this->getUser(),
            'points' => $studentPoints,
            'pointHistory' => $pointHistory,
            'addPointsForm' => $pointsForm->createView(),
            'positionTotal' => $rankingTotal,
            'positionInTeam' => $rankingByTeam,
            'positionInGeneration' => $rankingByGeneration,
            'positionInClass' => $rankingByClass,
        ]);
    }
}
