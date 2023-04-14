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
			return $this->response(404, "User not found");
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
				return $this->response(404, "User not found");
			}

			$request = $this->transformJsonBody($request);
			if (!$request || !$request->get("name")){
				throw new \Exception();
			}

			$user->setName($request->get("name"));
			$user->setDescription($request->get("description"));
			$user->setPassword($request->get("password"));
			$user->setEmail($request->get("email"));
			$user->setAvatarID($request->get("avatarid"));
			$user->SetRoles(json_decode($request->get("roles")));
			$entityManager->flush();

			return $this->response(200, "POST_UPDATE_SUCCESS");
		}catch (\Exception $e){
			return $this->response(422, "Invalid data");
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
			return $this->response(404, "User not found");
		}

		$entityManager->remove($user);
		$entityManager->flush();
		return $this->response(200, "USER_DELETE_SUCCESS");
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

			$user = $userRepository->findOneBy(["name" => $request->get("name")]);

			if ($user) {
				return $this->response(409, "User already exists");
			} else {
				$user = new User();
				$user->setName($request->get("name"));
				$user->setDescription($request->get("description"));
				$user->setPassword($request->get("password"));
				$user->setEmail($request->get("email"));
				$user->setAvatarID($request->get("avatarid"));
				$user->SetRoles(json_decode($request->get("roles")));
				
				$entityManager->persist($user);
				$entityManager->flush();
				return $this->response(200, "USER_ADD_SUCCESS");
			}
		} catch (\Exception $e){
			return $this->response(422, "Invalid data");
		}
	}
	public function response($status, $message)
	{
		$data = [
			"status" => $status,
			"message" => $message
		];
		return new JsonResponse($data, 200, []);
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
