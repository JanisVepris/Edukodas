<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\UserBundle\Entity\User;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;

class LoadUserData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface
{
    const BATCH_SIZE = 20;

    /**
     * @var StudentTeam[]
     */
    private $teams;

    /**
     * @var StudentClass[]
     */
    private $classes;

    /**
     * @var StudentGeneration[]
     */
    private $generations;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @return StudentTeam[]
     */
    private function getTeams()
    {
        return [
            $this->getReference('team_1'),
            $this->getReference('team_2'),
            $this->getReference('team_3'),
            $this->getReference('team_4'),
        ];
    }

    /**
     * @return StudentClass[]
     */
    private function getClasses()
    {
        return [
            $this->getReference('class_1'),
            $this->getReference('class_2'),
            $this->getReference('class_3'),
            $this->getReference('class_4'),
            $this->getReference('class_5'),
            $this->getReference('class_6'),
            $this->getReference('class_7'),
            $this->getReference('class_8'),
        ];
    }

    private function getGenerations()
    {
        return [
            $this->getReference('generation_1'),
            $this->getReference('generation_2'),
        ];
    }

    /**
     * Get userData
     *
     * @return array
     */
    private function getDefaultUsers()
    {
        return [
            [
                'username' => 'mokytojasa',
                'role' => 'ROLE_TEACHER',
            ],
            [
                'username' => 'mokytojasb',
                'role' => 'ROLE_TEACHER',
            ],
            [
                'username' => 'mokinysa',
                'role' => 'ROLE_STUDENT',
            ],
            [
                'username' => 'mokinysb',
                'role' => 'ROLE_STUDENT',
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
        $this->teams = $this->getTeams();
        $this->classes = $this->getClasses();
        $this->generations = $this->getGenerations();
        $this->faker = \Faker\Factory::create('lt_LT');

        $defaultUsers = $this->getDefaultUsers();

        foreach ($defaultUsers as $defaultUser) {
            $user = $this->generateUser($defaultUser['username'], $defaultUser['role']);
            $this->addReference('user_' . $user->getUsername(), $user);
            $manager->persist($user);
        }

        $manager->flush();

        for ($i = 1; $i <= 100; $i++) {
            $user = $this->generateUser();

            $this->addReference('user_' . $i, $user);

            $manager->persist($user);

            if (($i % static::BATCH_SIZE) === 0) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

    /**
     * @param null $username
     * @param string $role
     * @param string $password
     * @param bool $enabled
     *
     * @return User
     */
    private function generateUser($username = null, $role = 'ROLE_STUDENT', $password = 'password', $enabled = true)
    {
        $name = $this->getName();

        $user = new User();
        $user
            ->setUsername($username ?: $this->faker->unique()->username)
            ->setRoles([$role])
            ->setFirstName($name['first'])
            ->setLastName($name['last'])
            ->setEmail($this->faker->unique()->safeEmail)
            ->setPlainPassword($password)
            ->setEnabled($enabled);

        if ($role === 'ROLE_STUDENT') {
            $user
                ->setStudentTeam($this->teams[array_rand($this->teams)])
                ->setStudentClass($this->classes[array_rand($this->classes)])
                ->setStudentGeneration($this->generations[array_rand($this->generations)]);
        }

        return $user;
    }

    /**
     * @return array
     */
    private function getName()
    {
        if (rand(0, 1)) {
            return [
                'first' => $this->faker->firstNameMale,
                'last' => $this->faker->lastNameMale,
            ];
        }

        return [
            'first' => $this->faker->firstNameFemale,
            'last' => $this->faker->lastNameFemale,
        ];
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
