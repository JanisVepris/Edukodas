<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\TasksBundle\Entity\Course;

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
     * Get courseData
     *
     * @return array
     */
    private function getStudents()
    {
        return [
            [
                'student' => $this->getReference('mokinysa'),
                'entryCount' => 25
            ],
            [
                'student' => $this->getReference('mokinysb'),
                'entryCount' => 25
            ],
            [
                'student' => $this->getReference('mokinysc'),
                'entryCount' => 25
            ],
            [
                'student' => $this->getReference('mokinysd'),
                'entryCount' => 25
            ],
        ];
    }

    public function getTeachers()
    {
        return [
            $this->getReference('mokytojasa'),
            $this->getReference('mokytojasb'),
        ];
    }

    public function getTasks()
    {
        return [
            $this->getReference('task_0'),
            $this->getReference('task_1'),
            $this->getReference('task_2'),
            $this->getReference('task_3'),
            $this->getReference('task_4'),
            $this->getReference('task_5'),
            $this->getReference('task_6'),
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
        $students = $this->getStudents();
        $teachers = $this->getTeachers();
        $tasks = $this->getTasks();

        foreach ($students as $student) {
            for ($i = 0; $i < $student['entryCount']; $i++) {
                $pointHistory = new PointHistory();
                $pointHistory
                    ->setTeacher($teachers[array_rand($teachers)])
                    ->setStudent($student['student'])
                    ->setTask($tasks[array_rand($tasks)])
                    ->setComment('test comment')
                    ->setAmount(rand(-50, 50));
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
