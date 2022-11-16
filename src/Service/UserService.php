<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{

    public function __construct(private UserRepository $userRepository) {

    }

    public function findAllUsers(): array {
        return $this->userRepository->findAll();
    }
}