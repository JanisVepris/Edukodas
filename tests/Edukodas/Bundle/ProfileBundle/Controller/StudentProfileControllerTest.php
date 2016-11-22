<?php

namespace Test\Edukodas\Bundle\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentProfileControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/student/profile/', ['id' => 6]);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Mokinys A")')->count()
        );
    }
}
