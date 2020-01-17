<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImprintControllerTest extends WebTestCase
{
    public function testShowPage() {
        $client = static::createClient();
        $client->request('GET', '/imprint.html');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}