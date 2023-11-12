<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegisterFormType;
use App\Form\LoginFormType;
use Guzzle\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
	public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
	{
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		if ($error != null) {
			$error = $error->getMessage();
		}

		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => ['message' => $error]]);
	}

    #[Route(path: '/logout', name: 'logout')]
	public function logout(): void
	{
		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}

    #[Route('/register', name: 'register')]
	public function register(Request $request): Response
	{
		if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }
		$user = new User;
		$form = $this->createForm(RegisterFormType::class, $user);
		$form->handleRequest($request);
		$response = null;
		$error = null;

		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->getData()->password != $form->getData()->confirm_password) {
				$error = ['message' => "CONFIRM_PASSWORD_MISMATCH"];
				return $this->render('home/register.html.twig', [
					"form" => $form->createView(),
					"error" => $error
				]);
			}
			
			try {
				$client = new \GuzzleHttp\Client();
				$response = $client->post($this->getParameter('api.baseurl') . '/users', [
					'headers' => ['Content-Type' => 'application/json'],
					'body' => json_encode([
						'name' => $form->getData()->name,
						'description' => '',
						'password' => $form->getData()->password,
						'email' => $form->getData()->email,
						'avatarid' => ''
					])
				]);
				return $this->redirectToRoute('login');
			} catch (\Exception $e) {
				$error = json_decode($e->getResponse()->getBody(), true);
				return $this->render('home/register.html.twig', [
					"form" => $form->createView(),
					'error' => $error
				]);
			}
		}
		return $this->render('home/register.html.twig', [
			"form" => $form->createView(),
			"error" => $error
		]);
	}
}
