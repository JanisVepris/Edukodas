<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {
    protected function renderLogin(array $data)
    {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('edukodas_user_homepage');
        }

        return $this->render('FOSUserBundle:Security:login.html.twig', $data);
    }
}
