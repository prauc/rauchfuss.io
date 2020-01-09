<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CVControllerTest extends WebTestCase
{
    public function testShowPage() {
        $client = static::createClient();
        $client->request('GET', '/cv');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}