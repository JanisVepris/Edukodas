<?php

namespace Edukodas\Bundle\StatisticsBundle\Controller;

use Edukodas\Bundle\StatisticsBundle\Form\GraphType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GraphController extends Controller
{
    public function indexAction()
    {


        $filterForm = $this->createForm(GraphType::class);

        return $this->render('@EdukodasTemplate/Graph/graph.html.twig', [
            'filterForm' => $filterForm->createView()
        ]);
    }
}
