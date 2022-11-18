<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{

    public function __construct(private UserRepository $userRepository) {

    }

    public function findAllUsers(): array {
        return $this->userRepository->findAll();
    }

    public function findByName($name): array {
        return $this->userRepository->findBy(array('name' => $name));
    }

    public function findById($id): User {
        return $this->userRepository->findOneBy(array('id' => $id));
    }

    public function createUser()
    {

    }
}