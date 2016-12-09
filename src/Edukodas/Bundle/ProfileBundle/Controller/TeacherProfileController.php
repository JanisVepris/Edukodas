<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TeacherProfileController extends Controller
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

        if (!in_array('ROLE_TEACHER', $user->getRoles())) {
            throw new HttpException(403, 'User is not teachers');
        }

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
}
