<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *
 */
class DefaultControllerTest extends WebTestCase
{

    /**
     *
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()
        ->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')
        ->text());
    }
}
