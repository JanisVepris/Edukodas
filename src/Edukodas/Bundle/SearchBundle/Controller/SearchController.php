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
    public function searchTeacherAction(string $searchString)
    {
        return new JsonResponse();
    }

    /**
     * @param string $searchString
     * @return JsonResponse
     */
    public function searchStudentAction(string $searchString)
    {
        return new JsonResponse();
    }
}
