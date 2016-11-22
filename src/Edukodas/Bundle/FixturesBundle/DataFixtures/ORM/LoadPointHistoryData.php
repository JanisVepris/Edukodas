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
    private function getPointHistoryData()
    {
        return [
            [
                'teacher' => 'mokytojasa',
                'student' => 'mokinysa',
                'task'    => '1',
                'comment' => 'Pirmas teigiamas irasas',
                'amount'  => +2,
            ],
            [
                'teacher' => 'mokytojasa',
                'student' => 'mokinysa',
                'task'    => '2',
                'comment' => 'Pirmas neigiamas irasas',
                'amount'  => -2,
            ],
            [
                'teacher' => 'mokytojasb',
                'student' => 'mokinysa',
                'task'    => '3',
                'comment' => 'Antras teigiamas irasas',
                'amount'  => +1,
            ],
            [
                'teacher' => 'mokytojasa',
                'student' => 'mokinysa',
                'task'    => '1',
                'comment' => 'Antras neigiamas irasas',
                'amount'  => -3,
            ],
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
        $data = $this->getPointHistoryData();

        foreach ($data as $row) {
            $teacher = $this->getReference($row['teacher']);
            $student = $this->getReference($row['student']);
            $task = $this->getReference('task_' . $row['task']);

            $pointHistory = new PointHistory();
            $pointHistory
                ->setTeacher($teacher)
                ->setStudent($student)
                ->setTask($task)
                ->setComment($row['comment'])
                ->setAmount($row['amount']);

            $manager->persist($pointHistory);
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
        return 5;
    }
}
