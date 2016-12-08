<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;

class LoadStudentClassData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface
{
    /**
     * Get studentClassData
     *
     * @return array
     */
    private function getStudentClassData()
    {
        return [
            [
                'refnum' => 1,
                'title' => '1a',
            ],
            [
                'refnum' => 2,
                'title' => '1b',
            ],
            [
                'refnum' => 3,
                'title' => '2a'
            ],
            [
                'refnum' => 4,
                'title' => '2b'
            ],
            [
                'refnum' => 5,
                'title' => '3a'
            ],
            [
                'refnum' => 6,
                'title' => '3b'
            ],
            [
                'refnum' => 7,
                'title' => '4a'
            ],
            [
                'refnum' => 8,
                'title' => '4b'
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
        $data = $this->getStudentClassData();

        foreach ($data as $row) {
            $studentClass = new StudentClass();
            $studentClass
                ->setTitle($row['title']);
            $manager->persist($studentClass);
            $this->addReference('class_' . $row['refnum'], $studentClass);
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
        return 1;
    }
}
