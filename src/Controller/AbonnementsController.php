<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementsController extends AbstractController
{
    /**
     * @Route("/abonnements", name="abonnements")
     */
    public function index()
    {
        return $this->render('abonnements/index.html.twig', [
            'controller_name' => 'AbonnementsController',
        ]);
    }
}
