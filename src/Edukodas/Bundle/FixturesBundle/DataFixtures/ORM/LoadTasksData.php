<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\TasksBundle\Entity\Task;

class LoadTasksData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface
{
    /**
     * Get tasksData
     *
     * @return array
     */
    private function getTasksData()
    {
        return [
            [
                'refnum' => 1,
                'course' => $this->getReference('course_1'),
                'taskName' => 'Namų darbai',
            ],
            [
                'refnum' => 2,
                'course' => $this->getReference('course_1'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 3,
                'course' => $this->getReference('course_2'),
                'taskName' => 'Namų darbai',
            ],
            [
                'refnum' => 4,
                'course' => $this->getReference('course_2'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 5,
                'course' => $this->getReference('course_3'),
                'taskName' => 'Namų darbai',
            ],
            [
                'refnum' => 6,
                'course' => $this->getReference('course_3'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 7,
                'course' => $this->getReference('course_4'),
                'taskName' => 'Nauji žodžiai',
            ],
            [
                'refnum' => 8,
                'course' => $this->getReference('course_4'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 9,
                'course' => $this->getReference('course_5'),
                'taskName' => 'Pamokos praleidimas',
            ],
            [
                'refnum' => 10,
                'course' => $this->getReference('course_5'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 11,
                'course' => $this->getReference('course_6'),
                'taskName' => 'Trigonometrija',
            ],
            [
                'refnum' => 12,
                'course' => $this->getReference('course_6'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 13,
                'course' => $this->getReference('course_7'),
                'taskName' => 'Iniciatyva',
            ],
            [
                'refnum' => 14,
                'course' => $this->getReference('course_7'),
                'taskName' => 'Aktyvumas',
            ],
            [
                'refnum' => 15,
                'course' => $this->getReference('course_8'),
                'taskName' => 'Iniciatyva',
            ],
            [
                'refnum' => 16,
                'course' => $this->getReference('course_8'),
                'taskName' => 'Aktyvumas',
            ],
        ];
    }

    /**
     * Loads tasks fixtures into database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $data = $this->getTasksData();

        foreach ($data as $taskData) {
            $task = new Task();
            $task
                ->setName($taskData['taskName'])
                ->setDescription($faker->text)
                ->setPoints($faker->numberBetween(-20, 40))
                ->setCourse($taskData['course']);
            $manager->persist($task);

            $this->addReference('task_' . $taskData['refnum'], $task);
        }

        $manager->flush();
    }

    /**
     * Get order
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
