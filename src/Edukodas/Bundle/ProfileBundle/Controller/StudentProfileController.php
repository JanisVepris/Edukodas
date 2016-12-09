<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentProfileController extends Controller
{
    /**
     * @param User|null $user
     * @return Response
     */
    public function indexAction(User $user = null)
    {
        if ($user === null) {
            $user = $this->getUser();
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
