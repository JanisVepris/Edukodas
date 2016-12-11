<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Edukodas\Bundle\UserBundle\Entity\User;
use Edukodas\Bundle\UserBundle\Form\ProfileEditType;
use Edukodas\Bundle\UserBundle\Form\RemoveProfilePictureType;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Edukodas\Bundle\UserBundle\Form\ProfilePictureType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends BaseController
{
    /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        if ($user->hasPicture()) {
            $user->setPicture(
                new File($this->getParameter('profile_pic_dir') . '/' . $user->getPicturePath())
            );
        }

        $form_picture = $this->createForm(ProfilePictureType::class, $user);
        $form_picture->setData($user);
        $form_picture->handleRequest($request);

        if ($form_picture->isValid()) {
            $request->getSession()->getFlashBag()->clear();
            /** @var UploadedFile $picture */
            $picture = $user->getPicture();

            if ($picture) {
                $filename = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move(
                    $this->getParameter('profile_pic_dir'),
                    $filename
                );

                $user->setPicturePath($filename);
            }

            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_edit');

                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::PROFILE_EDIT_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        $form_profile = $this->createForm(ProfileEditType::class, $user);
        $form_profile->setData($user);

        $form_profile->handleRequest($request);

        if ($form_profile->isValid()) {
            $request->getSession()->getFlashBag()->clear();
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form_profile, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_edit');

                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::PROFILE_EDIT_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.change_password.form.factory');

        $form_password = $formFactory->createForm();
        $form_password->setData($user);

        $form_password->handleRequest($request);

        if ($form_password->isValid()) {
            $request->getSession()->getFlashBag()->clear();
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form_password, $request);
            $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_edit');

                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(
                FOSUserEvents::CHANGE_PASSWORD_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        $form_remove = $this->createForm(RemoveProfilePictureType::class);

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form_profile' => $form_profile->createView(),
            'form_password' => $form_password->createView(),
            'form_picture' => $form_picture->createView(),
            'form_remove' => $form_remove->createView(),
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function removePictureAction()
    {
        $user = $this->getUser();

        if (!$user->hasPicture()) {
            new RedirectResponse($this->generateUrl('fos_user_profile_edit'));
        }

        $fullPath = $this->getParameter('profile_pic_dir') . '/' . $user->getPicturePath();

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $user->setPicture(null);
        $user->setPicturePath(null);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->generateUrl('fos_user_profile_edit'));
    }
}
