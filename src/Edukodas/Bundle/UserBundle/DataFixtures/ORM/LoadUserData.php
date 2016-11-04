<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        //Genadij
        $user = new User();
        $user->setUsername('genadijb');
        $user->getSalt(md5(uniqid()));

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setEmail('genadij.bojev@gmail.com');
        $user->setFirstName('Genadij');
        $user->setLastName('Bojev');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        //Lukas
        $user = new User();
        $user->setUsername('lukasc');
        $user->getSalt(md5(uniqid()));

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setEmail('lukasceplikas@gmail.com');
        $user->setFirstName('Lukas');
        $user->setLastName('Ceplikas');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        //Domantas
        $user = new User();
        $user->setUsername('domantasp');
        $user->getSalt(md5(uniqid()));

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setEmail('domantas.pet@gmail.com');
        $user->setFirstName('Domantas');
        $user->setLastName('Petrauskas');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        //Mokytojas1
        $user = new User();
        $user->setUsername('teacher');
        $user->getSalt(md5(uniqid()));

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'teacher');
        $user->setPassword($password);
        $user->setEmail('mokytojas@mokytojas.com');
        $user->setFirstName('Mokytojas');
        $user->setLastName('Mokytojauskas');
        $user->setRoles(['ROLE_TEACHER']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        //Mokinys
        $user = new User();
        $user->setUsername('student');
        $user->getSalt(md5(uniqid()));

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'student');
        $user->setPassword($password);
        $user->setEmail('mokinys@mokinys.com');
        $user->setFirstName('Mokinys');
        $user->setLastName('Mokiniauskas');
        $user->setRoles(['ROLE_STUDENT']);
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();
    }
}