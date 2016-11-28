<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements
    FixtureInterface,
    ContainerAwareInterface,
    OrderedFixtureInterface
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
                'enabled' => true,
                'generation' => null,
                'team' => $this->getReference('team_1')
            ],
            [
                'username' => 'domantasp',
                'firstName' => 'Domantas',
                'lastName' => 'Petrauskas',
                'password' => 'admin',
                'email' => 'domantas.pet@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'enabled' => true,
                'generation' => null,
                'team' => $this->getReference('team_1')
            ],
            [
                'username' => 'lukasc',
                'firstName' => 'Lukas',
                'lastName' => 'Ceplikas',
                'password' => 'admin',
                'email' => 'lukasceplikas@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'enabled' => true,
                'generation' => null,
                'team' => $this->getReference('team_1')
            ],
            [
                'username' => 'mokytojasa',
                'firstName' => 'Mokytojas',
                'lastName' => 'A',
                'password' => 'password',
                'email' => 'mokytojasa@pastas.com',
                'roles' => ['ROLE_TEACHER'],
                'enabled' => true,
                'generation' => null,
                'team' => null
            ],
            [
                'username' => 'mokytojasb',
                'firstName' => 'Mokytojas',
                'lastName' => 'B',
                'password' => 'password',
                'email' => 'mokytojasb@pastas.com',
                'roles' => ['ROLE_TEACHER'],
                'enabled' => true,
                'generation' => null,
                'team' => null
            ],
            [
                'username' => 'mokinysa',
                'firstName' => 'Mokinys',
                'studentClass' => '4c',
                'lastName' => 'A',
                'password' => 'password',
                'email' => 'mokinysa@pastas.com',
                'roles' => ['ROLE_STUDENT'],
                'enabled' => true,
                'generation' => $this->getReference('generation_2016'),
                'team' => $this->getReference('team_1')
            ],
            [
                'username' => 'mokinysb',
                'firstName' => 'Mokinys',
                'studentClass' => '4b',
                'lastName' => 'B',
                'password' => 'password',
                'email' => 'mokinysb@pastas.com',
                'roles' => ['ROLE_STUDENT'],
                'enabled' => true,
                'generation' => $this->getReference('generation_2016'),
                'team' => $this->getReference('team_2')
            ],
            [
                'username' => 'mokinysc',
                'firstName' => 'Mokinys',
                'studentClass' => '4a',
                'lastName' => 'C',
                'password' => 'password',
                'email' => 'mokinysc@pastas.com',
                'roles' => ['ROLE_STUDENT'],
                'enabled' => true,
                'generation' => $this->getReference('generation_2016'),
                'team' => $this->getReference('team_3')
            ],
            [
                'username' => 'mokinysd',
                'firstName' => 'Mokinys',
                'studentClass' => '4a',
                'lastName' => 'D',
                'password' => 'password',
                'email' => 'mokinysd@pastas.com',
                'roles' => ['ROLE_STUDENT'],
                'enabled' => true,
                'generation' => $this->getReference('generation_2016'),
                'team' => $this->getReference('team_4')
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

        foreach ($data as $userData) {
            $user = new User();
            $user
                ->setUsername($userData['username'])
                ->setFirstName($userData['firstName'])
                ->setLastName($userData['lastName'])
                ->setPlainPassword($userData['password'])
                ->setEmail($userData['email'])
                ->setRoles($userData['roles'])
                ->setEnabled($userData['enabled']);

            if ($userData['generation']) {
                $user->setStudentGeneration($userData['generation']);
            }

            if ($userData['team']) {
                $user->setStudentTeam($userData['team']);
            }

            if (isset($userData['studentClass'])) {
                $studentClass = $this->getReference('class_' . $userData['studentClass']);
                $user->setStudentClass($studentClass);
            }

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
        return 2;
    }
}
