<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeepAliveController extends AbstractController
{
    #[Route('/api/keep-alive', name: 'app_keep_alive')]
    public function index(Request $request): Response
    {
        $request->getSession()->migrate();
		return JsonResponse::fromJsonString('{"result":"OK"}', 200);
    }
}
