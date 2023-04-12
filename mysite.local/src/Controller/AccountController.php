<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/settings', name: 'settings')]
    public function settings(): Response
    {
        return $this->render('account/settings.html.twig', []);
    }
    #[Route('/account', name: 'account')]
    public function account(): Response
    {
        $user = $this->security->getUser();
        return $this->render('account/account.html.twig', [
            "name" => $user->getName(),
            "description" => $user->getDescription()
        ]);
    }
}
