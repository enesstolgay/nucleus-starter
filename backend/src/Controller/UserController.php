<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
