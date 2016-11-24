<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->getUser()->hasRole('ROLE_TEACHER')) {
                return $this->redirectToRoute('edukodas_teacher_profile');
            }

            return $this->redirectToRoute('edukodas_student_profile');
        }

        return $this->render('FOSUserBundle:Security:login.html.twig', $data);
    }


}
