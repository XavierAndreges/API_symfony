<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class TableService
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->connection = $entityManager->getConnection();
    }

    public function getAllDataFromTable(string $table): array
    {
        try {
            // Vérification de sécurité pour éviter les injections SQL
            if (!preg_match('/^[A-Za-z]+$/', $table)) {
                throw new \InvalidArgumentException('Invalid table name');
            }

            // Préparation de la requête
            $sql = "SELECT * FROM {$table}";
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->executeQuery();

            // Récupération des résultats
            $data = $result->fetchAllAssociative();

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 