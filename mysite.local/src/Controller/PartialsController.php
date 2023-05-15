<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Guzzle\Client;


#[Route('/partials', name: 'app_partials')]
class PartialsController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post_partial')]
    public function postPartial(int $id): Response
    {
        $client = new \GuzzleHttp\Client();
        return $this->render('partials/postPartial.html.twig', [
        	'width' => $width,
        	'height' => $height,
            'title' => "Test",
            'prompt' => "hello world",
            'description' => "hello world",
            'likes' => 12,
            'dislikes' => 3
        ]);
    }
}
