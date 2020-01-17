<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CVController extends AbstractController
{
    /**
     * @Route(
     *     "/cv.{format}",
     *     name="cv",
     *     format="html"
     * )
     */
    public function index()
    {
        return $this->render('cv/index.html.twig', [
            'title' => 'CV',
        ]);
    }
}
