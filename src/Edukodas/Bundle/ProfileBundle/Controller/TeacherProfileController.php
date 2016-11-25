<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeacherProfileController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        return $this->render('EdukodasTemplateBundle:Profile:teacherprofile.html.twig', [
            'user' => $user
        ]);
    }
}
