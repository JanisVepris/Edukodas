<?php

namespace UserBundle\DataFixture\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\UserBundle\Entity\StudentGeneration;

class LoadStudentGenerationData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $generation = new StudentGeneration();

        $generation
            ->setTitle('2015')
            ->setYear(new \DateTime('-1 year'));

        $this->setReference('generation_1', $generation);

        $manager->persist($generation);

        $generation = new StudentGeneration();

        $generation
            ->setTitle('2016')
            ->setYear(new \DateTime('Y'));

        $this->setReference('generation_2', $generation);

        $manager->persist($generation);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
