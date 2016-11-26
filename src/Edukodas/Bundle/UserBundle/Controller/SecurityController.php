<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Edukodas\Bundle\UserBundle\Entity\User;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            if ($this->getUser()->hasRole(User::TEACHER_ROLE)) {
                return $this->redirectToRoute('edukodas_teacher_profile');
            }

            if ($this->getUser()->hasRole(User::STUDENT_ROLE)) {
                return $this->redirectToRoute('edukodas_student_profile');
            }

            return $this->redirectToRoute('edukodas_user_homepage');
        }

        return $this->render('FOSUserBundle:Security:login.html.twig', $data);
    }
}
