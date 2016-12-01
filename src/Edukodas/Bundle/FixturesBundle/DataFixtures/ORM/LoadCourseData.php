<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\TasksBundle\Entity\Course;

class LoadCourseData extends AbstractFixture implements
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
    private function getCourseData()
    {
        return [
            [
                'refnum' => 1,
                'teacher' => $this->getReference('user_mokytojasa'),
                'courseName' => 'Anglų kalba',
            ],
            [
                'refnum' => 2,
                'teacher' => $this->getReference('user_mokytojasa'),
                'courseName' => 'Vokiečių kalba ',
            ],
            [
                'refnum' => 3,
                'teacher' => $this->getReference('user_mokytojasa'),
                'courseName' => 'Prancūzų kalba',
            ],
            [
                'refnum' => 4,
                'teacher' => $this->getReference('user_mokytojasa'),
                'courseName' => 'Lietuvių kalba',
            ],
            [
                'refnum' => 5,
                'teacher' => $this->getReference('user_mokytojasb'),
                'courseName' => 'Matematika',
            ],
            [
                'refnum' => 6,
                'teacher' => $this->getReference('user_mokytojasb'),
                'courseName' => 'Fizika',
            ],
            [
                'refnum' => 7,
                'teacher' => $this->getReference('user_mokytojasb'),
                'courseName' => 'Chemija',
            ],
            [
                'refnum' => 8,
                'teacher' => $this->getReference('user_mokytojasb'),
                'courseName' => 'Geografija',
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
        $data = $this->getCourseData();

        foreach ($data as $courseData) {
            $course = new Course();
            $course
                ->setName($courseData['courseName'])
                ->setUser($courseData['teacher']);
            $manager->persist($course);

            $this->addReference('course_' . $courseData['refnum'], $course);
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
