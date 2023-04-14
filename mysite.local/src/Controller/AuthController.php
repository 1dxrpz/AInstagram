<?php

namespace App\Controller;

use App\Entity\User;
use Guzzle\Client;
use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormError;

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

		$user = new User;
		$form = $this->createForm(LoginFormType::class, $user);
		$form->handleRequest($request);
		if ($error) {
			$form->addError(new FormError("Wrong name or password"));
		}

		return $this->render('security/login.html.twig', [
			'form' => $form->createView(),
			'last_username' => $lastUsername
		]);
	}

    #[Route(path: '/logout', name: 'logout')]
	public function logout(): void 
	{ }

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
		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->getData()->password != $form->getData()->confirm_password) {
				$form->addError(new FormError("Password and confirm password doesn't match"));
			} else {
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
						'avatarid' => '',
						'roles' => json_encode(array("ROLE_USER"))
					])
				]);
			}
		}
		
		if ($response != null) {
			$error = json_decode($response->getBody()->getContents(), true);
			if ($error['status'] == 200) {
				return $this->redirectToRoute('login');
			} else
				$form->addError(new FormError($error['message']));
		}
		return $this->render('home/register.html.twig', [
			"form" => $form->createView()
		]);

	}
}
