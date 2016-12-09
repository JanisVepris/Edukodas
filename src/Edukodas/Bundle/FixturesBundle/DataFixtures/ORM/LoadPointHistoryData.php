<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\UserBundle\Entity\User;

class LoadPointHistoryData extends AbstractFixture implements
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
     * @return Task[]
     */
    private function getTasks()
    {
        return [
            $this->getReference('task_1'),
            $this->getReference('task_2'),
            $this->getReference('task_3'),
            $this->getReference('task_4'),
            $this->getReference('task_5'),
            $this->getReference('task_6'),
            $this->getReference('task_7'),
            $this->getReference('task_8'),
            $this->getReference('task_9'),
            $this->getReference('task_10'),
            $this->getReference('task_11'),
            $this->getReference('task_12'),
            $this->getReference('task_13'),
            $this->getReference('task_14'),
            $this->getReference('task_15'),
            $this->getReference('task_16'),
        ];
    }

    /**
     * Loads course fixtures into database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $students = $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class)
            ->findAllStudents();

        $tasks = $this->getTasks();

        foreach ($students as $student) {
            for ($i = 0; $i < $faker->numberBetween(10, 25); $i++) {
                $randomTask = $tasks[array_rand($tasks)];

                $pointHistory = new PointHistory();
                $pointHistory
                    ->setTeacher($randomTask->getOwner())
                    ->setStudent($student)
                    ->setTask($randomTask)
                    ->setComment($faker->sentence)
                    ->setAmount($faker->numberBetween(-20, 40))
                    ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));
                $manager->persist($pointHistory);
            }

            $manager->flush();
        }
    }

    /**
     * Get order
     *
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
