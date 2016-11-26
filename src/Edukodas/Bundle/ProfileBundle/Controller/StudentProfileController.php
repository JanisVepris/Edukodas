<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

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

        $pointHistory = $this
            ->getDoctrine()
            ->getRepository('EdukodasStatisticsBundle:PointHistory')
            ->findBy(['student' => $user]);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->render('EdukodasTemplateBundle:Profile:studentProfile.html.twig', [
            'user' => $user,
            'pointHistory' => $pointHistory,
        ]);
    }
}
