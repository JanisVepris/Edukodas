<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class AbstractTeacherController extends Controller
{
    /**
     * Checks if user owns a task
     *
     * @param Task $task
     */
    protected function checkOwnerOr403(Task $task)
    {
        if ($task->getCourse()->getUser()->getId() !== $this->getUser()->getId()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }

    /**
     * Checks if user has teacher role
     */
    protected function checkTeacherOr403()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->hasRole('ROLE_TEACHER')) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
