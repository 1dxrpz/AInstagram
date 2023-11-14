<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Post;
use App\Form\PostUploadFormType;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

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
    
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        $user = $this->security->getUser();
        if ($user == null) {
            return $this->redirectToRoute('login');
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->getParameter('api.baseurl') . '/users');
        $users = json_decode($response->getBody(), true);
        
        //return new JsonResponse($users);

        return $this->render('home/admin.html.twig', ["users" => $users]);
    }

    #[Route('/generate', name: 'generate')]
    public function generate(): Response
    {
        $user = $this->security->getUser();
        if ($user == null) {
            return $this->redirectToRoute('login');
        }

        return $this->render('home/generate.html.twig', []);
    }

    #[Route('/upload', name: 'upload')]
    public function upload(Request $request): Response
    {
        $user = $this->security->getUser();
        if ($user == null) {
            return $this->redirectToRoute('login');
        }

        $post = new Post();

        $form = $this->createForm(PostUploadFormType::class, $post);

        $form->handleRequest($request);
        $response = null;
        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $client = new \GuzzleHttp\Client();
                $userId = $user->getId();
                $response = $client->post($this->getParameter('api.baseurl') . '/posts/' . $userId, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        "prompt" => $form->getData()->Prompt,
                        "description" => $form->getData()->Description,
                        "imageurl" => $form->getData()->ImageURL,
                        "title" => $form->getData()->Title
                    ])
                ]);
            } catch (ClientException $e) {
                if ($e->getResponse()->getStatusCode() === 422) {
                    $error = json_decode($e->getResponse()->getBody(), true);
                    return $this->render('home/upload.html.twig', ['error' => $error]);
                    $error = $e;
                }
                $error = $e;
            }

        }
        return $this->render('home/upload.html.twig', [
            "form" => $form->createView(),
            "response" => json_encode($response),
            "error" => $error
        ]);
    }
}
