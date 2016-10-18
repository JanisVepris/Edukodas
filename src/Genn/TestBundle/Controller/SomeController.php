<?php

namespace Genn\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SomeController extends Controller
{

    public function nameAction($name)
    {
        return $this->render('GennTestBundle:Default:index.html.twig', array(
            'name' => $name
,        ));
    }

    public function numberAction($max)
    {
        $number = mt_rand(0, $max);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

}
