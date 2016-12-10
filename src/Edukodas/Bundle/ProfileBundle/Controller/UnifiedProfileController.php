<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UnifiedProfileController extends Controller
{
    /**
     * @param User|null $user
     * @return mixed
     */
    public function profileAction(User $user = null)
    {
        if ($user === null) {
            $user = $this->getUser();
        }
        if ($user->hasRole('ROLE_TEACHER')) {
            return $this->teacherProfileAction($user);
        } elseif ($user->hasRole('ROLE_STUDENT')) {
            return $this->studentProfileAction($user);
        } else {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @param User|null $user
     * @return Response
     */
    public function teacherProfileAction(User $user = null)
    {
        $pointHistory = $this
            ->get('edukodas.pointhistory.repository')
            ->getRecentEntriesByTeacher($user);

        $pointsTotalPositive = $this
            ->get('edukodas.pointhistory.repository')
            ->getTotalPositivePointsByTeacher($user);

        $pointsTotalNegative = $this
            ->get('edukodas.pointhistory.repository')
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

    /**
     * @param User|null $user
     * @return Response
     */
    public function studentProfileAction(User $user = null)
    {
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
