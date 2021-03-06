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
        $encoders = [new XmlEncoder([
            XMLEncoder::ROOT_NODE_NAME => 'urlset'
        ])];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $routes = $router->getRouteCollection();
        $sitemap = ['@xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9', 'url' => []];
        foreach($routes as $route => $arguments) {
            if(preg_match("/(sitemap|_error)/i", $arguments->getPath()) != true) {
                $path = $this->generateUrl($route, ['format' => 'html']);

                list($controller_namespace,) = explode("::", $arguments->getDefault("_controller"));
                $controller_path = explode("\\", $controller_namespace);
                $controller = __DIR__ . '/' . end($controller_path) . ".php";

                $node = new Sitemap();
                $node->setLoc("https://" . $request->getHttpHost() . $path);
                $node->setLastmod(file_exists($controller) ? new \DateTime(date("Y-m-d", filemtime($controller))) : new \DateTime());

                array_push($sitemap['url'], $node);
            }
        }

        return new Response($serializer->serialize($sitemap, 'xml'));
    }
}
