<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Edukodas\Bundle\UserBundle\Controller\AbstractTeacherController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PointHistoryController extends AbstractTeacherController
{
    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('@EdukodasTemplate/Profile/inc/_listPointHistory.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
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

            $isStudentProfile = $request->request->get('isStudentProfile') ? true : false;

            return $this->render('@EdukodasTemplate/Profile/inc/_listPointHistory.html.twig', [
                'entryId' => $pointHistory->getId(),
                'amount' => $pointHistory->getAmount(),
                'studentName' => $pointHistory->getStudent()->getFullName(),
                'teacher' => $pointHistory->getTeacher(),
                'entryOwnerId' => $pointHistory->getOwner()->getId(),
                'taskName' => $pointHistory->getTask()->getName(),
                'comment' => $pointHistory->getComment(),
                'createdAt' => $pointHistory->getCreatedAt()->format('Y/m/d H:m'),
                'isStudentProfile' => $isStudentProfile,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('@EdukodasTemplate/Profile/inc/_addPointHistoryForm.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @param int $pointHistoryId
     * @return Response
     */
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

        $isStudentProfile = $request->request->get('isStudentProfile') ? true : false;

        $currentStudentId = $pointHistory->getStudent()->getId();

        $user = $this->getUser();

        $form = $this->createForm(PointHistoryType::class, $pointHistory, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PointHistory $pointHistory */
            $newPointHistory = $form->getData();
            $newPointHistory->setTeacher($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newPointHistory);
            $em->flush();

            if ($isStudentProfile && ($currentStudentId !== $newPointHistory->getStudent()->getId())) {
                return new Response(' ');
            }

            return $this->render('@EdukodasTemplate/Profile/inc/_listPointHistory.html.twig', [
                'entryId' => $newPointHistory->getId(),
                'amount' => $newPointHistory->getAmount(),
                'studentName' => $newPointHistory->getStudent()->getFullName(),
                'teacher' => $newPointHistory->getTeacher(),
                'entryOwnerId' => $newPointHistory->getOwner()->getId(),
                'taskName' => $newPointHistory->getTask()->getName(),
                'comment' => $newPointHistory->getComment(),
                'createdAt' => $pointHistory->getCreatedAt()->format('Y/m/d H:m'),
                'isStudentProfile' => $isStudentProfile,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('@EdukodasTemplate/Profile/inc/_editPointHistoryForm.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('@EdukodasTemplate/Profile/inc/_editPointHistoryForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int $pointHistoryId
     * @return Response
     */
    public function deleteAction(int $pointHistoryId)
    {
        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->find($pointHistoryId);

        if (!$pointHistory) {
            throw new NotFoundHttpException('Point history not found');
        }

        $this->checkOwnerOr403($pointHistory);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($pointHistory);
        $em->flush();

        return new Response('', Response::HTTP_OK);
    }
}
