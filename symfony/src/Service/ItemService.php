<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class ItemService
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->connection = $entityManager->getConnection();
    }

    public function getItemData(string $table, string $idRepName): array
    {
        try {
            // Vérification de sécurité pour éviter les injections SQL
            if (!preg_match('/^[A-Za-z]+$/', $table)) {
                throw new \InvalidArgumentException('Invalid table name');
            }

            // Première requête : récupérer les données de l'item
            $sql = "SELECT * FROM {$table} WHERE idRepName = :idRepName";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('idRepName', $idRepName);
            $result = $stmt->executeQuery();
            $itemData = $result->fetchAssociative();

            if (!$itemData) {
                return [
                    'success' => false,
                    'error' => 'Item not found'
                ];
            }

            // Deuxième requête : récupérer les images associées
            $sql = "SELECT * FROM Pictures WHERE idRepName = :idRepName AND tableName = :table";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('idRepName', $idRepName);
            $stmt->bindValue('table', $table);
            $result = $stmt->executeQuery();
            $pictures = $result->fetchAllAssociative();

            return [
                'success' => true,
                'data' => [
                    'item' => $itemData,
                    'pictures' => $pictures
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 