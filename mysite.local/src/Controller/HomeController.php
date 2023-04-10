<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }
    #[Route('/explore', name: 'explore')]
    public function explore(): Response
    {
        return $this->render('home/explore.html.twig', []);
    }
    #[Route('/upload', name: 'upload')]
    public function upload(): Response
    {
        return $this->render('home/upload.html.twig', []);
    }
    /*
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('home/login.html.twig', []);
    } */
    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('home/register.html.twig', []);
    }
}
