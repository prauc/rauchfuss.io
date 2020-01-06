<?php

namespace App\Controller;

use App\Entity\Sitemap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SitemapController extends AbstractController
{
    /**
     * @Route(
     *     "/sitemap.{format}",
     *     name="sitemap",
     *     format="xml"
     * )
     */
    public function index(Request $request, RouterInterface $router)
    {
        $encoders = [new XmlEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $routes = $router->getRouteCollection();
        $sitemap = ['@xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9', 'url' => []];
        foreach($routes as $route) {
            $path = $route->getPath();

            if(preg_match("/(sitemap|_error)/i", $path) != true) {
                $node = new Sitemap();
                $node->setLoc("https://" . $request->getHttpHost() . $path);
                $node->setLastmod(new \DateTime());

                array_push($sitemap['url'], $node);
            }
        }

        return new Response($serializer->serialize($sitemap, 'xml'));
    }
}
