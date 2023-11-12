<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partials', name: 'app_partials')]
class PartialsController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post_partial')]
    public function postPartial(int $id): Response
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->getParameter('api.baseurl') . '/posts/' . $id);
        $data = json_decode($response->getBody()->getContents());

        return $this->render('partials/postPartial.html.twig', [
            'title' => $data->Title,
            'prompt' => $data->Prompt,
            'description' => $data->Description,
            'imageURL' => $data->ImageURL,
            'likes' => 12,
            'dislikes' => 3
        ]);
    }
    #[Route('/posts', name: 'app_posts_partial')]
    public function postsPartial(): Response
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->getParameter('api.baseurl') . '/posts');
        $data = json_decode($response->getBody()->getContents());

        return new JsonResponse($data);
    }
}
