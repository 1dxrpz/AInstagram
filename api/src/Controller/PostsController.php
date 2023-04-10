<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* Class PostsController
* @package App\Controller
* @Route("/api", name="posts_api")
*/
class PostsController extends AbstractController
{
	/**
	* @param postRepository $postRepository
	* @return JsonResponse
	* @Route("/posts", name="posts_getall", methods={"GET"})
	*/
	public function getPosts(postRepository $postRepository){
		$data = $postRepository->findAll();
		$posts = $data;
		//var_dump($posts);
		//die;
		return $this->response($data);
	}
	/**
   * @param postRepository $postRepository
   * @param $id
   * @return JsonResponse
   * @Route("/posts/{id}", name="posts_getbyid", methods={"GET"})
   */
	public function getUsersById(postRepository $postRepository, $id){
		$post = $postRepository->find($id);

		if (!$post){
			$data = [
				'status' => 404,
				'errors' => "post not found",
			];
			return $this->response($data, 404);
		}
		return $this->response($post);
	}
	/**
   * @param Request $request
   * @param EntityManagerInterface $entityManager
   * @param postRepository $postRepository
   * @param $id
   * @return JsonResponse
   * @Route("/posts/{id}", name="posts_put", methods={"PUT"})
   */
	public function updatePost(Request $request, EntityManagerInterface $entityManager, postRepository $postRepository, $id){
		try{
			$post = $postRepository->find($id);

			if (!$post){
				$data = [
					'status' => 404,
					'errors' => "Post not found",
				];
				return $this->response($data, 404);
			}

			$request = $this->transformJsonBody($request);
			/*
			if (!$request || !$request->get('name') || !$request->request->get('description')){
				throw new \Exception();
			}
			*/

			$post->setPrompt($request->get('prompt'));
			$post->setDescription($request->get('description'));
			$post->setImageID($request->get('imageid'));
			$entityManager->flush();

			$data = [
				'status' => 200,
				'errors' => "Post updated successfully",
			];
			return $this->response($data);
		}catch (\Exception $e){
			$data = [
				'status' => 422,
				'errors' => "Data no valid",
			];
			return $this->response($data, 422);
		}
	}
	/**
   * @param postRepository $postRepository
   * @param $id
   * @return JsonResponse
   * @Route("/posts/{id}", name="post_delete", methods={"DELETE"})
   */
	public function deletePost(EntityManagerInterface $entityManager, postRepository $postRepository, $id){
		$post = $postRepository->find($id);

		if (!$post){
			$data = [
				'status' => 404,
				'errors' => "Post not found",
			];
			return $this->response($data, 404);
		}

		$entityManager->remove($post);
		$entityManager->flush();
		$data = [
			'status' => 200,
			'errors' => "Post deleted successfully",
		];
		return $this->response($data);
	}
	/**
	* @param Request $request
	* @param EntityManagerInterface $entityManager
	* @param postRepository $postRepository
	* @return JsonResponse
	* @throws \Exception
	* @Route("/posts", name="posts_add", methods={"POST"})
	*/
	public function addPost(Request $request, EntityManagerInterface $entityManager, postRepository $postRepository){
		try{
			$request = $this->transformJsonBody($request);
			/*
			if (!$request || !$request->get('name') || !$request->request->get('description')){
				throw new \Exception();
			}
			*/
			
			$post = new Post();
			$post->setPrompt($request->get('prompt'));
			$post->setDescription($request->get('description'));
			$post->setImageID($request->get('imageid'));
			$entityManager->persist($post);
			$entityManager->flush();

			$data = [
				'status' => 200,
				'success' => "Post added successfully",
			];
			return $this->response($data);

		}catch (\Exception $e){
			$data = [
				'status' => 422,
				'errors' => "Data not valid",
			];
			return $this->response($data, 422);
		}
	}
	public function response($data, $status = 200, $headers = [])
	{
		return new JsonResponse($data, $status, $headers);
	}
	protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
	{
		$data = json_decode($request->getContent(), true);

		if ($data === null) {
			return $request;
		}

		$request->request->replace($data);

		return $request;
	}
}
