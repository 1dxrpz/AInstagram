<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserPosts;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\UserPostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* Class UserPostsController
* @package App\Controller
* @Route("/api", name="user_posts")
*/
class UserPostsController extends AbstractController
{
	/**
	* @param Request $request
	* @param EntityManagerInterface $entityManager
	* @param UserPostsRepository $userPostsRepository
	* @param $postId
	* @param $userId
	* @return JsonResponse
	* @throws \Exception
	* @Route("/posts/{postId}/{userId}", name="user_posts_add", methods={"POST"})
	*/
	public function addUserPost(Request $request, 
		EntityManagerInterface $entityManager, 
		UserPostsRepository $userPostsRepository,
		$postId, $userId
	){
		try{
			$request = $this->transformJsonBody($request);
			/*
			if (!$request || !$request->get('name') || !$request->request->get('description')){
				throw new \Exception();
			}
			*/
			
			$userPost = new UserPosts();
			$userPost->setPostID($postId);
			$userPost->setUserID($userId);
			$entityManager->persist($userPost);
			$entityManager->flush();

			$data = [
				'message' => "USER_POST_ADD_SUCCESS"
			];
			return $this->response($data, 200);

		}catch (\Exception $e){
			$data = ['message' => "INVALID_DATA"];
			return $this->response($data, 422);
		}
	}
	/**
	* @param userPostsRepository $userPostsRepository
	* @param userRepository $userRepository
	* @param postRepository $postRepository
	* @param $id
	* @return JsonResponse
	* @Route("/userposts/{id}", name="get_by_user_id", methods={"GET"})
	*/
	public function getPostsByUserId(
		userPostsRepository $userPostRepository,
		postRepository $postRepository,
		$id
	){
		$data = $postRepository->findAll();
		$posts = $data;
		return $this->response($data);
	}
	public function response($data, $status = 200, $headers = [])
	{
		return new JsonResponse($data, $status, $headers);
	}
}