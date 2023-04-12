<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Security\User;

class UserProvider extends ServiceEntityRepository implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        $entityManager = $this->getEntityManager();
        $user = $entityManager->createQuery(
                'SELECT u
                FROM App\Security\User u
                WHERE u.name = :query
                OR u.email = :query'
            )
            ->setParameter('query', $identifier)
            ->getOneOrNullResult();
        if ($user == null) {
            throw new UserNotFoundException('User not found', 0, null, ['error' => 'User not found']);
        }
        return $user;
    }
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }
        return $user;
    }
    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: when hashed passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newHashedPassword);
    }
}
