<?php

namespace Edukodas\Bundle\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TeacherProfileController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render('EdukodasProfileBundle::teacherprofile.html.twig', [
            'user' => $user
        ]);
    }
}
