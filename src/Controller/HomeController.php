<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HomeController extends AbstractController
{
    private Serializer $serialize;

    public function __construct(private UserService $userService) {
        $this->serialize = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    #[Route('/home', name: 'app_home')]
    public function show(): Response {
        $users = $this->userService->findAllUsers();

        return new Response($this->serialize->serialize($users, 'json'));
    }

    #[Route('/home/{slug}', name: 'app_user_detail')]
    public function showUser($slug): Response {
        $users = $this->userService->findByName($slug);

        return new Response(($this->serialize->serialize($users, 'json')));
    }

    #[Route('/home/id/{slug}', name: 'app_user_id')]
    public function showUserById($slug): Response {
        $users = $this->userService->findById($slug);

        return new Response(($this->serialize->serialize($users, 'json')));
    }
}
