<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostUploadFormType;

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
    public function upload(Request $request): Response
    {
        
        $post = new Post();

        $form = $this->createForm(PostUploadFormType::class, $post);

        $form->handleRequest($request);
        $response = null;
        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->post($this->getParameter('api.baseurl') . '/posts', [
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
