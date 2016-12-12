<?php


namespace Edukodas\Bundle\FixturesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Edukodas\Bundle\UserBundle\Entity\StudentTeam;

class LoadStudentTeamData extends AbstractFixture implements
    FixtureInterface,
    OrderedFixtureInterface
{
    /**
     * @return array
     */
    private function getStudentTeamData()
    {
        return [
            [
                'refnum' => 1,
                'title' => 'Raudonieji',
                'color' => 'red',
                'graphColor' => 'ef5350'
            ],
            [
                'refnum' => 2,
                'title' => 'Mėlynieji',
                'color' => 'blue',
                'graphColor' => '42a5f5'
            ],
            [
                'refnum' => 3,
                'title' => 'Žalieji',
                'color' => 'green',
                'graphColor' => '66bb6a'
            ],
            [
                'refnum' => 4,
                'title' => 'Oranžiniai',
                'color' => 'orange',
                'graphColor' => 'ffa726'
            ],
        ];
    }

    /**
     * Loads student team fixtures into database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $teamData = $this->getStudentTeamData();

        foreach ($teamData as $data) {
            $team = new StudentTeam();
            $team
                ->setColor($data['color'])
                ->setGraphColor($data['graphColor'])
                ->setTitle($data['title']);

            $manager->persist($team);

            $this->addReference('team_' . $data['refnum'], $team);
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
