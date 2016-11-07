<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\TasksBundle\Entity\Course;

class LoadCourseData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function getCourseData()
    {
        return [
            [
                'username' => 'mokytojasa',
                'courseName' => 'Anglų kalba 1',
            ],
            [
                'username' => 'mokytojasa',
                'courseName' => 'Vokiečių kalba 1',
            ],
            [
                'username' => 'mokytojasb',
                'courseName' => 'Matematika 3',
            ],
        ];
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->getCourseData();

        foreach ($data as $courseData)
        {
            $user = $this->getReference($courseData['username']);

            $course = new Course();
            $course
                ->setCourseName($courseData['courseName'])
                ->setUser($user);
            $manager->persist($course);

            $this->addReference($courseData['courseName'], $course);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }
}