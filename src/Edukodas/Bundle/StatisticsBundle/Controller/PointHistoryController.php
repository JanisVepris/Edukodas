<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Controller\AbstractTeacherController;
use Symfony\Component\HttpFoundation\Request;

class PointHistoryController extends AbstractTeacherController
{
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('EdukodasTemplateBundle:pointhistory:listpoints.html.twig', [
            'user' => $user,
        ]);
    }

    public function addAction(Request $request)
    {
        $this->checkTeacherOr403();

        $points = new PointHistory();

        $form = $this->createForm(PointHistoryType::class, $points, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PointHistory $pointHistory */
            $pointHistory = $form->getData();
            $pointHistory->setTeacher($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($pointHistory);
            $em->flush();

            return $this->render('EdukodasTemplateBundle:pointhistory:listpoints.html.twig', [
                'entryId' => $pointHistory->getId(),
                'amount' => $pointHistory->getAmount(),
                'studentName' => $pointHistory->getStudent()->getFullName(),
                'teacherName' => $pointHistory->getTeacher()->getFullName(),
                'taskName' => $pointHistory->getTask()->getName(),
                'comment' => $pointHistory->getComment(),
                'createdAt' => $pointHistory->getCreatedAt()->format('Y/m/d H:m')
            ]);
        }
    }
}
