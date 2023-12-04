<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_security_')]
class SecurityController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher,
                                private JWTTokenManagerInterface $JWTTokenManager)
    {}

    #[Route('/login', name: 'api_security_login', methods: 'POST')]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return $this->json('error not user', 400);
        }
        $passwordCheck = $this->passwordHasher->isPasswordValid($user, $data['password']);
        if (!$passwordCheck) {
            return $this->json('error not user', 400);
        }
        $token = $this->JWTTokenManager->create($user);
        $response = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'token' => $token,
            'roles' => $user->getRoles(),
        ];

        return $this->json($response, 201);
    }
}