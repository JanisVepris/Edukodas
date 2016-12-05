<?php

namespace Edukodas\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller
{
    /**
     * @param string $searchString
     * @return JsonResponse
     */
    public function searchUsersAction(string $searchString)
    {
        $students = $this
            ->getDoctrine()
            ->getRepository('EdukodasUserBundle:User')
            ->findStudentByString($searchString);

        $teachers = $this
            ->getDoctrine()
            ->getRepository('EdukodasUserBundle:User')
            ->findTeacherByString($searchString);

        return $this->render('EdukodasTemplateBundle:inc:_searchResultList.html.twig', [
            'students' => $students,
            'teachers' => $teachers
        ]);
    }
}
