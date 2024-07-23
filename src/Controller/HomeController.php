<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\ActiviteTest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController{
    #[Route('/', name: 'home')]
    public function home(Request $request,EntityManagerInterface $em):Response{
        return $this->render('home/index.html.twig');
    }
}