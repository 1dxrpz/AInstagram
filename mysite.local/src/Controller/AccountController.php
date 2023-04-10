<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    public function settings(): Response
    {
        return $this->render('account/settings.html.twig', []);
    }
    #[Route('/account', name: 'account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig', []);
    }
}
