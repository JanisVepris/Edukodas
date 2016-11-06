<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\TasksBundle\Entity\Task;

class LoadTasksData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function getTasksData()
    {
        return [
            [
                'courseName' => 'Anglu k.',
                'taskDescr' => 'Padaryti 10 namu darbu ir eiles',
                'taskPoints' => 10,
            ],
            [
                'courseName' => 'Anglu k.',
                'taskDescr' => 'Nepadaryti 5 namu darbu ir eiles',
                'taskPoints' => -5,
            ],
        ];
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->getTasksData();

        foreach ($data as $tasksData)
        {
            $course = $this->getReference($tasksData['courseName']);

            $task = new Task();
            $task
                ->setTaskDescr($tasksData['taskDescr'])
                ->setTaskPoints($tasksData['taskPoints'])
                ->setCourse($course);
            $manager->persist($task);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 3;
    }
}