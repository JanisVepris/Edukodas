<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Edukodas\Bundle\StatisticsBundle\Form\PointHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PointHistoryController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('EdukodasTemplateBundle:pointhistory:listpoints.html.twig', [
            'user' => $user,
        ]);
    }
}
