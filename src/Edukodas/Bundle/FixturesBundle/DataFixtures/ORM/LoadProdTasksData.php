<?php

namespace UserBundle\DataFixture\ORM;

use AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\TasksBundle\Entity\Course;
use Edukodas\Bundle\TasksBundle\Entity\Task;

class LoadProdTasksData extends AbstractDataFixture
{
    /**
     * @var Course[]
     */
    private $courses;

    /**
     * @return Course[]
     */
    private function getCourses()
    {
        return [
           $this->getReference('course_1'),
           $this->getReference('course_2'),
           $this->getReference('course_3'),
           $this->getReference('course_4'),
           $this->getReference('course_5'),
           $this->getReference('course_6'),
           $this->getReference('course_7'),
           $this->getReference('course_8'),
           $this->getReference('course_9'),
           $this->getReference('course_10'),
           $this->getReference('course_11'),
           $this->getReference('course_12'),
           $this->getReference('course_13'),
           $this->getReference('course_14'),
           $this->getReference('course_15'),
           $this->getReference('course_16'),
           $this->getReference('course_17'),
        ];
    }

    /**
     * @return array
     */
    private function getTaskNames()
    {
        return [
            'Namų darbai',
            'Aktyvumas',
            'Iniciatyva',
            'Disciplina',
            'Lankomumas',
            'Kita',
        ];
    }

    /**
     * @return array
     */
    private function generateTasks()
    {
        $taskNames = $this->getTaskNames();
        $courses = $this->getCourses();
        $i = 1;
        foreach ($courses as $course) {
            foreach ($taskNames as $taskName) {
                $tasksData[] = [
                    'refnum' => $i,
                    'course' => $course,
                    'taskName' => $taskName,
                ];
                $i++;
            }
        }
        return $tasksData;
    }

    /**
     * Loads tasks fixtures into database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function doLoad(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $data = $this->generateTasks();

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

    /**
     * Returns the environments the fixtures may be loaded in.
     *
     * @return array The name of the environments.
     */
    protected function getEnvironments()
    {
        return ['prod'];
    }
}
