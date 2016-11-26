<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Controller\AbstractTeacherController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $pointHistory = new PointHistory();

        $form = $this->createForm(PointHistoryType::class, $pointHistory, ['user' => $this->getUser()]);

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

    public function editAction(Request $request, int $pointHistoryId)
    {
        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->find($pointHistoryId);

        if (!$pointHistory) {
            throw new NotFoundHttpException('Point history not found');
        }

        $this->checkOwnerOr403($pointHistory);

        $user = $this->getUser();

        $form = $this->createForm(PointHistoryType::class, $pointHistory, ['user' => $user]);

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
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('EdukodasTemplateBundle:Profile/inc:_editPointHistoryForm.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('EdukodasTemplateBundle:Profile/inc:_editPointHistoryForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
