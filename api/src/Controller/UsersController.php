<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* Class UsersController
* @package App\Controller
* @Route("/api", name="users_api")
*/
class UsersController extends AbstractController
{
	/**
	* @param UserRepository $userRepository
	* @return JsonResponse
	* @Route("/users", name="users_getall", methods={"GET"})
	*/
	public function getUsers(UserRepository $userRepository){
		$data = $userRepository->findAll();
		return $this->response($data);
	}
	/**
   * @param UserRepository $userRepository
   * @param $id
   * @return JsonResponse
   * @Route("/users/{id}", name="users_getbyid", methods={"GET"})
   */
	public function getUsersById(UserRepository $userRepository, $id){
		$user = $userRepository->find($id);

		if (!$user){
			$data = ['message' => "USER_NOT_FOUND"];
			return $this->response($data, 404);
		}
		return $this->response($user);
	}
	/**
   * @param Request $request
   * @param EntityManagerInterface $entityManager
   * @param UserRepository $userRepository
   * @param $id
   * @return JsonResponse
   * @Route("/users/{id}", name="users_put", methods={"PUT"})
   */
	public function updateUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id){
		try{
			$user = $userRepository->find($id);

			if (!$user){
				$data = ['message' => "USER_NOT_FOUND"];
				return $this->response($data, 404);
			}

			$request = $this->transformJsonBody($request);
			if (!$request || !$request->get('name')){
				throw new \Exception();
			}

			$user->setName($request->get('name'));
			$user->setDescription($request->get('description'));
			$user->setPassword($request->get('password'));
			$user->setEmail($request->get('email'));
			$user->setAvatarID($request->get('avatarid'));
			$entityManager->flush();

			$data = ['message' => "POST_UPDATE_SUCCESS"];
			return $this->response($data, 200);
		}catch (\Exception $e){
			$data = ['message' => "INVALID_DATA"];
			return $this->response($data, 422);
		}
	}
	/**
   * @param UserRepository $userRepository
   * @param $id
   * @return JsonResponse
   * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
   */
	public function deleteUser(EntityManagerInterface $entityManager, UserRepository $userRepository, $id){
		$user = $userRepository->find($id);

		if (!$user){
			$data = ['message' => "USER_NOT_FOUND"];
			return $this->response($data, 404);
		}

		$entityManager->remove($user);
		$entityManager->flush();
		$data = ['message' => "USER_DELETE_SUCCESS"];
		return $this->response($data, 200);
	}
	/**
	* @param Request $request
	* @param EntityManagerInterface $entityManager
	* @param UserRepository $userRepository
	* @return JsonResponse
	* @throws \Exception
	* @Route("/users", name="users_add", methods={"POST"})
	*/
	public function addUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository){
		try{
			$request = $this->transformJsonBody($request);

			$user = $userRepository->findOneBy(['name' => $request->get('name')]);

			if ($user) {
				$data = ['message' => "USER_ALREADY_EXISTS"];
				return $this->response($data, 409);
			} else {
				$user = new User();
				$user->setName($request->get('name'));
				$user->setDescription($request->get('description'));
				$user->setPassword($request->get('password'));
				$user->setEmail($request->get('email'));
				$user->setAvatarID($request->get('avatarid'));
				$entityManager->persist($user);
				$entityManager->flush();
				$data = ['message' => "USER_ADD_SUCCESS"];
				return $this->response($data, 200);
			}
		} catch (\Exception $e){
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
}
