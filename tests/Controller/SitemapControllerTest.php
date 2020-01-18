<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SitemapControllerTest extends WebTestCase
{
    private function crawlSite() {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        return $client;
    }

    public function testShowPage() {
        $client = $this->crawlSite();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'text/xml; charset=UTF-8'
            ),
            'the "Content-Type" header is "text/xml; charset=UTF-8"'
        );
    }

    public function testXMLContent() {
        $client = $this->crawlSite();
        $crawler = $client->getCrawler();

        $this->assertEquals(1, $crawler->filter('urlset')->count());
        $this->assertGreaterThan(
            3,
            $crawler->filter('url')->count()
        );
    }
}