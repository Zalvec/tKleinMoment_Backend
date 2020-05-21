<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
//    /**
//     * @Route("/home", name="home")
//     */
//    public function index()
//    {
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
//    }

    /**
     * @return Response
     * @Route("/menu", name="app_menu")
     */
    public function getMenu(){
        return new Response("<h1>all menu items</h1>");
    }

    /**
     * @return Response
     * @Route("/logout", name="app_logout")
     */
    public function logout(){
        return new Response("<h1>logged out</h1>");
    }
}
