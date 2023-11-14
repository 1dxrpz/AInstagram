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
		if ($user == null) {
			return $this->redirectToRoute('login');
		}
		$posts = array();
		try {
			$client = new \GuzzleHttp\Client();
			$response = $client->get($this->getParameter('api.baseurl') . '/posts/' . $user->getId());
			$posts = json_decode($response->getBody(), true);
		} catch (\Exception $e) {

		}

		return $this->render('account/account.html.twig', [
			"name" => $user->getName(),
			"description" => $user->getDescription(),
			"role" => $user->getRoles()[0],
			"posts" => $posts
		]);
	}
    #[Route('/user/{id}', name: 'user_account')]
	public function user_account($id): Response
	{
		$user = null;
		try {
			$client = new \GuzzleHttp\Client();
			$response = $client->get($this->getParameter('api.baseurl') . '/users/' . $id);
			$user = json_decode($response->getBody(), true);
		} catch (\Exception $e) {
			return $this->redirectToRoute('index');
		}

		$posts = array();
		try {
			$client = new \GuzzleHttp\Client();
			$response = $client->get($this->getParameter('api.baseurl') . '/posts/' . $user.id);
			$posts = json_decode($response->getBody(), true);
		} catch (\Exception $e) {

		}

		return $this->render('account/account.html.twig', [
			"name" => $user["name"],
			"description" => $user["description"],
			"role" => $user["roles"][0],
			"posts" => $posts
		]);
	}
}
