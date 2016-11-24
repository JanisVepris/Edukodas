<?php

namespace Test\Edukodas\Bundle\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class StudentProfileControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexAction()
    {
        $this->logIn();

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'mokinysa',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $crawler = $this->client->request('GET', '/student/profile/', ['id' => 6]);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Mokinys A")')->count()
        );
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context (defaults to the firewall name)
        $firewall = 'secured_area';

        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
