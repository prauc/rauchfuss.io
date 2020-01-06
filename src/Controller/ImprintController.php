<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImprintController extends AbstractController
{
    /**
     * @Route("/imprint", name="imprint")
     */
    public function index()
    {
        dump($this->getParameter('kernel.environment'));
        return $this->render('imprint/index.html.twig', [
            'title' => 'Imprint',
        ]);
    }
}
