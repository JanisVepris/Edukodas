<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Set container
     *
     * @param ContainerInterface|null $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get userData
     *
     * @return array
     */
    private function getUserData()
    {
        return [
            [
                'username' => 'genadijb',
                'firstName' => 'Genadij',
                'lastName' => 'Bojev',
                'password' => 'admin',
                'email' => 'genadij.bojev@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'enabled' => true
            ],
            [
                'username' => 'domantasp',
                'firstName' => 'Domantas',
                'lastName' => 'Petrauskas',
                'password' => 'admin',
                'email' => 'domantas.pet@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'enabled' => true
            ],
            [
                'username' => 'lukasc',
                'firstName' => 'Lukas',
                'lastName' => 'Ceplikas',
                'password' => 'admin',
                'email' => 'lukasceplikas@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'enabled' => true
            ],
            [
                'username' => 'mokytojasa',
                'firstName' => 'Mokytojas',
                'lastName' => 'A',
                'password' => 'password',
                'email' => 'mokytojasa@pastas.com',
                'roles' => ['ROLE_TEACHER'],
                'enabled' => true
            ],
            [
                'username' => 'mokytojasb',
                'firstName' => 'Mokytojas',
                'lastName' => 'B',
                'password' => 'password',
                'email' => 'mokytojasb@pastas.com',
                'roles' => ['ROLE_TEACHER'],
                'enabled' => true
            ],
            [
                'username' => 'mokinysa',
                'firstName' => 'Mokinys',
                'lastName' => 'A',
                'password' => 'password',
                'email' => 'mokinysa@pastas.com',
                'roles' => ['ROLE_USER'],
                'enabled' => true
            ],
            [
                'username' => 'mokinysb',
                'firstName' => 'Mokinys',
                'lastName' => 'B',
                'password' => 'password',
                'email' => 'mokinysb@pastas.com',
                'roles' => ['ROLE_USER'],
                'enabled' => true
            ],            [
                'username' => 'mokinysc',
                'firstName' => 'Mokinys',
                'lastName' => 'C',
                'password' => 'password',
                'email' => 'mokinysc@pastas.com',
                'roles' => ['ROLE_USER'],
                'enabled' => true
            ],
        ];
    }

    /**
     * Loads user entities into database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $data = $this->getUserData();

        foreach ($data as $userData)
        {
            $user = new User();
            $user
                ->setUsername($userData['username'])
                ->setFirstName($userData['firstName'])
                ->setLastName($userData['lastName'])
                ->setPlainPassword($userData['password'])
                ->setEmail($userData['email'])
                ->setRoles($userData['roles'])
                ->setEnabled($userData['enabled']);
            $manager->persist($user);

            $this->addReference($userData['username'], $user);
        }

        $manager->flush();
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
