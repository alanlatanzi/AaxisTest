<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractController
{
    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $password = $data['password'];

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['username' => $username]);
        if ($user) {
            return new JsonResponse(['message' => 'User already exists'], 409);
        } else {
            $user = new \App\Entity\User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $entityManager->persist($user);
            $entityManager->flush();
            
            return new JsonResponse(['message' => 'User registered successfully'], 201);
        }
    }

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $password = $data['password'];

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        if (!password_verify($password, $user->getPassword())) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        $user->setToken(bin2hex(random_bytes(50)));
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            [
                'message' => 'User logged in successfully',
                'token' => $user->getToken()
            ],
            200
        );
    }
    /**
     * @Route("/api/logout", name="api_logout", methods={"POST"})
     */
    public function logout(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(\App\Entity\User::class)->findOneBy(['token' => $token]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $user->setToken('');
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            [
                'message' => 'User logged out successfully'
            ],
            200
        );
    }
}
