<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Returns an empty response as a placeholder for dashboard
     *
     * @return Response
     */
    public function indexAction()
    {
        return new Response('');
    }
}
