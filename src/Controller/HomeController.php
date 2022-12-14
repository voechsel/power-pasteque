<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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

    #[Route('/home/add/', name: 'app_user_add')]
    public function addUser(ManagerRegistry $doctrine, Request $request): Response {
        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($data['name']);
        $user->setMatos($data['matos']);
        $user->setHaircolor($data['haircolor']);

        if ($this->userService->findByName($data['name'])) {
            return new Response('This name is already taken');
        } else {
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response(($this->serialize->serialize($user, 'json')));
        }
    }

    #[Route('/home/delete/{id}', name: 'app_user_delete')]
    public function deleteUser(ManagerRegistry $doctrine, Request $request, $id): Response {
        $entityManager = $doctrine->getManager();

        $user = $this->userService->findById($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response();
    }

    #[Route('/home/{name}', name: 'app_user_detail')]
    public function showUser($name): Response {
        $user = $this->userService->findByName($name);

        return new Response(($this->serialize->serialize($user, 'json')));
    }

    #[Route('/home/id/{id}', name: 'app_user_id')]
    public function showUserById($id): Response {
        $user = $this->userService->findById($id);

        return new Response(($this->serialize->serialize($user, 'json')));
    }

    #[Route('/home/clone/{id}', name: 'app_user_clone')]
    public function cloneUser(ManagerRegistry $doctrine, $id): Response {
        $entityManager = $doctrine->getManager();
        $user = $this->userService->findById($id);
        $user_clone = clone $user;
        $user_clone->setName($user_clone->getName().' (cloned)');

        $entityManager->persist($user_clone);
        $entityManager->flush();
        return new Response($this->serialize->serialize($user_clone, 'json'));
    }

    #[Route('/home/edit/{id}', name: 'app_user_edit')]
    public function updateUser(ManagerRegistry $doctrine, Request $request, $id): Response {
        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->findById($id);

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['matos'])) {
            $user->setMatos($data['matos']);
        }

        if (isset($data['haircolor'])) {
            $user->setHaircolor($data['haircolor']);
        }

        $entityManager->flush();
        return new Response(($this->serialize->serialize($user, 'json')));
    }
}
