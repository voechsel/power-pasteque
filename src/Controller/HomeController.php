<?php

namespace App\Controller;

use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/home/{name}', name: 'app_user_detail')]
    public function showUser($name): Response {
        $users = $this->userService->findByName($name);

        return new Response(($this->serialize->serialize($users, 'json')));
    }

    #[Route('/home/id/{id}', name: 'app_user_id')]
    public function showUserById($id): Response {
        $users = $this->userService->findById($id);

        return new Response(($this->serialize->serialize($users, 'json')));
    }

    #[Route('/home/edit/{id}', name: 'user_edit')]
    public function updateUser(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $users = $this->userService->findById($id);
        $users->setName('Vincent');
        $entityManager->flush();

        return new Response(($this->serialize->serialize($users, 'json')));
    }
}
