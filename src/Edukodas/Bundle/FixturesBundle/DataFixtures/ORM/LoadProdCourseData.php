<?php

namespace UserBundle\DataFixture\ORM;

use AbstractDataFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\TasksBundle\Entity\Course;

class LoadProdCourseData extends AbstractDataFixture
{
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
            [
                'refnum' => 9,
                'teacher' => $this->getReference('user_mokytojasc'),
                'courseName' => 'Informatika',
            ],
            [
                'refnum' => 10,
                'teacher' => $this->getReference('user_mokytojasd'),
                'courseName' => 'Darbeliai',
            ],
            [
                'refnum' => 11,
                'teacher' => $this->getReference('user_mokytojase'),
                'courseName' => 'Kūno kultūra',
            ],
            [
                'refnum' => 12,
                'teacher' => $this->getReference('user_mokytojasf'),
                'courseName' => 'Istorija',
            ],
            [
                'refnum' => 13,
                'teacher' => $this->getReference('user_mokytojasf'),
                'courseName' => 'Politologija',
            ],
            [
                'refnum' => 14,
                'teacher' => $this->getReference('user_mokytojasg'),
                'courseName' => 'Etika',
            ],
            [
                'refnum' => 15,
                'teacher' => $this->getReference('user_mokytojash'),
                'courseName' => 'Matematika 2',
            ],
            [
                'refnum' => 16,
                'teacher' => $this->getReference('user_mokytojash'),
                'courseName' => 'Fizika 2',
            ],
            [
                'refnum' => 17,
                'teacher' => $this->getReference('user_mokytojash'),
                'courseName' => 'Chemija 2',
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
    public function doLoad(ObjectManager $manager)
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
