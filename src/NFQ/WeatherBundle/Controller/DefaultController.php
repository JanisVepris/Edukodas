<?php

namespace NFQ\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/weather")
     */
    public function indexAction()
    {
        return $this->render('NFQWeatherBundle:Default:index.html.twig');
    }
}
