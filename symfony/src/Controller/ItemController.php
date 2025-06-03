<?php

namespace App\Controller;

use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    #[Route('/{table}/{idRepName}', name: 'item_route', requirements: ['table' => '[A-Za-z]+', 'idRepName' => '[A-Za-z0-9_-]+'], methods: ['GET'])]
    public function index(string $table, string $idRepName, Request $request): JsonResponse
    {
        $result = $this->itemService->getItemData($table, $idRepName);
        
        return $this->json($result);
    }
} 