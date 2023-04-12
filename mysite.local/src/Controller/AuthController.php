<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegisterFormType;
use Guzzle\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
			try {
				$client = new \GuzzleHttp\Client();
				$response = $client->post($this->getParameter('api.baseurl') . '/users', [
					'headers' => [
						'Content-Type' => 'application/json',
					],
					'body' => json_encode([
						'name' => $form->getData()->name,
						'description' => '',
						'password' => $form->getData()->password,
						'email' => $form->getData()->email,
						'avatarid' => ''
					])
				]);
			} catch (ClientException $e) {
				if ($e->getResponse()->getStatusCode() === 422) {
					$error = json_decode($e->getResponse()->getBody(), true);
					return $this->render('security/login.html.twig', ['error' => $error]);
					$error = $e;
				}
				$error = $e;
			}

		}
		$response = new RedirectResponse('/login');
		return $this->render('home/register.html.twig', [
			"form" => $form->createView(),
            "response" => json_encode($response),
			"error" => $error
		]);

	}
}
