<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImprintController extends AbstractController
{
    /**
     * @Route(
     *     "/imprint.{format}",
     *     name="imprint",
     *     format="html"
     * )
     */
    public function index()
    {
        return $this->render('imprint/index.html.twig', [
            'title' => 'Imprint',
        ]);
    }
}
