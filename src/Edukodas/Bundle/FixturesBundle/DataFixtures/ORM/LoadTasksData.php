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
     * Get tasksData
     *
     * @return array
     */
    private function getTasksData()
    {
        return [
            [
                'courseName' => 'Anglų kalba 1',
                'taskName' => 'Namų darbai',
                'taskDescr' => 'Padaryti 10 namų darbų iš eilės',
                'taskPoints' => 10,
            ],
            [
                'courseName' => 'Anglų kalba 1',
                'taskName' => 'Namų darbai',
                'taskDescr' => 'Nepadaryti 5 namų darbų iš eilės',
                'taskPoints' => -5,
            ],
            [
                'courseName' => 'Vokiečių kalba 1',
                'taskName' => 'Nauji žodžiai',
                'taskDescr' => 'Išmokti 10 naujų žodžių',
                'taskPoints' => 3,
            ],
            [
                'courseName' => 'Vokiečių kalba 1',
                'taskName' => 'Pamokos praleidimas',
                'taskDescr' => 'Praleisti pamoką be priežasties',
                'taskPoints' => -15,
            ],
            [
                'courseName' => 'Matematika 3',
                'taskName' => 'Trigonometrija',
                'taskDescr' => 'Supainioti tan ir ctg',
                'taskPoints' => -1,
            ],
            [
                'courseName' => 'Matematika 3',
                'taskName' => 'Iniciatyva',
                'taskDescr' => 'Sudalyvauti Kengūroje',
                'taskPoints' => 17,
            ],
            [
                'courseName' => 'Matematika 3',
                'taskName' => 'Iniciatyva',
                'taskDescr' => 'Pasisiūlyti prie lentos',
                'taskPoints' => 10,
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
        $data = $this->getTasksData();

        foreach ($data as $tasksData)
        {
            $course = $this->getReference($tasksData['courseName']);

            $task = new Task();
            $task
                ->setTaskName($tasksData['taskName'])
                ->setTaskDescr($tasksData['taskDescr'])
                ->setTaskPoints($tasksData['taskPoints'])
                ->setCourse($course);
            $manager->persist($task);
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
        return 3;
    }
}
