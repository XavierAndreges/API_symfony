<?php

namespace App\Controller;

use App\Service\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TableController extends AbstractController
{
    private TableService $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    #[Route('/{table}', name: 'table_route', requirements: ['table' => '[A-Za-z]+'], methods: ['GET'])]
    public function index(string $table, Request $request): JsonResponse
    {
        $result = $this->tableService->getAllDataFromTable($table);
        
        return $this->json($result);
    }
} 