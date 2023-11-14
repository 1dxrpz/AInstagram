<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\UserPosts;
use App\Repository\PostRepository;
use App\Repository\UserPostsRepository;
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
	private ?EntityManagerInterface $entityManager = null;

	public function __construct(EntityManagerInterface $entityManager)
	{
        $this->entityManager = $entityManager;
    }

	/**
	* @param userPostsRepository $userPostsRepository
	* @param $id
	* @return JsonResponse
	* @Route("/posts/user/{id}", name="posts_getbyuser", methods={"GET"})
	*/
	public function getPostsByUser(userPostsRepository $userPostsRepository, $id){
		$data = $userPostsRepository->findByUserId($id);
		return $this->response($data);
	}

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
			$data = ['message' => "POST_NOT_FOUND"];
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
				$data = ['message' => "POST_NOT_FOUND",];
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
			$post->setImageURL($request->get('imageurl'));
			$post->setTitle($request->get('title'));
			$entityManager->flush();

			$this->createLog($post->getId(), "Post " . $post->getId() . " Updated");

			$data = ['message' => "POST_UPDATE_SUCCESS"];
			return $this->response($data, 200);
		}catch (\Exception $e){
			$data = ['message' => "INVALID_DATA"];
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
			$data = ['message' => "POST_NOT_FOUND"];
			return $this->response($data, 404);
		}

		$entityManager->remove($post);
		$entityManager->flush();

		$this->createLog($post->getId(), "Post " . $post->getId() . " Removed");

		$data = ['message' => "POST_DELETE_SUCCESS"];
		return $this->response($data, 200);
	}
	/**
	* @param Request $request
	* @param $id
	* @param EntityManagerInterface $entityManager
	* @param postRepository $postRepository
	* @param userPostsRepository $userPostsRepository
	* @return JsonResponse
	* @throws \Exception
	* @Route("/posts/{id}", name="posts_add", methods={"POST"})
	*/
	public function addPost(
		Request $request,
		$id,
		EntityManagerInterface $entityManager, 
		postRepository $postRepository,
		userPostsRepository $userPostsRepository
	){
		try{
			$request = $this->transformJsonBody($request);
			/*
			if (!$request || !$request->get('name') || !$request->request->get('description')){
				throw new \Exception();
			}
			*/
			
			$post = new Post(
				$request->get('prompt'),
				$request->get('description'),
				$request->get('imageurl'),
				$request->get('title')
			);
			$postRepository->save($post);
			$userPosts = new UserPosts(
				$post->getId(),
				$id
			);
			$userPostsRepository->save($userPosts);
			$entityManager->flush();

			$this->createLog($post->getId(), "Post " . $post->getId() . " Created");

			$data = ['message' => "POST_ADD_SUCCESS"];
			return $this->response($data, 200);

		}catch (\Exception $e){
			$data = ['message' => "INVALID_DATA"];
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

	private function createLog($id, $reason) {
		$log = new Log(
			$id,
			new \DateTime(),
			$reason
		);
		$this->entityManager->persist($log);
		$this->entityManager->flush();
	}
}
