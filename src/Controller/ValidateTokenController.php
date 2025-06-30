<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ValidateTokenController extends AbstractController
{
    #[Route('/api/token/validate', name: 'app_token_validate')]
    public function index(Request $request): JsonResponse
    {
        // The route is protected by JWT token, any access mean the token is valid.
        return $this->json(['valid' => true]);
    }
}
