<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Edukodas\Bundle\UserBundle\Entity\StudentClass;

class LoadStudentClassData extends AbstractFixture implements
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
     * Get studentClassData
     *
     * @return array
     */
    private function getStudentClassData()
    {
        return [
            [
                'title' => '1',
            ],
            [
                'title' => '2',
            ],
            [
                'title' => '3',
            ],
            [
                'title' => '4a',
            ],
            [
                'title' => '4b',
            ],
            [
                'title' => '4c'
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
            $this->addReference('class_' . $row['title'], $studentClass);
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
