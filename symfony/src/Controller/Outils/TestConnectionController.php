<?php

namespace App\Controller\Outils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class TestConnectionController extends AbstractController
{
    #[Route('/outils/test-connection', name: 'app_test_connection')]
    public function index(Connection $connection): Response
    {
        try {
            // Test de la connexion
            $connection->executeQuery('SELECT 1');
            
            // Si on arrive ici, la connexion est réussie
            $message = 'Connexion à la base de données réussie !';
            $status = 'success';
        } catch (\Exception $e) {
            // En cas d'erreur
            $message = 'Erreur de connexion : ' . $e->getMessage();
            $status = 'error';
        }

        return $this->render('outils/test_connection/index.html.twig', [
            'message' => $message,
            'status' => $status
        ]);
    }
} 