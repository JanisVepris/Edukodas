<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
            return $this->redirectToRoute('fos_user_security_login');

        return $this->redirectToRoute('edukodas_user_homepage');
    }

    /**
     * @Route("/list", name="posts_list")
     */
    public function listAction()
    {
        $exampleService = $this->get('app.example');

        $posts = $exampleService->getPosts();

        return $this->render('AppBundle:Home:list.html.twig', [
            'posts' => $posts,
        ]);
    }
}
