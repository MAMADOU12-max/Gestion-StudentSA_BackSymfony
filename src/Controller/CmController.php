<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CmController extends AbstractController
{
    /**
     * @Route("/cm", name="cm")
     */
    public function index(): Response
    {
        return $this->render('cm/index.html.twig', [
            'controller_name' => 'CmController',
        ]);
    }
}
