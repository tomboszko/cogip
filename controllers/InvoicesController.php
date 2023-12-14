<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\InvoicesModel;
use App\Utilities\Pagination;
use Exception;

class InvoicesController extends Controller
{
    private $invoicesModel;

    public function __construct()
    {
        $this->invoicesModel = new InvoicesModel();
    }

    public function getAllInvoices()
    {
        try {
            // Récupérez les paramètres de pagination depuis l'URL
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

            // Créez une instance de Pagination
            $pagination = new Pagination($perPage, $page);

            // Utilisez le modèle pour récupérer toutes les factures paginées
            $results = $this->invoicesModel->getAllInvoices($pagination);

            // Envoyez les résultats au format JSON
            header('Content-Type: application/json');
            echo json_encode($results, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Gérez les erreurs ici
            http_response_code(500); // Code d'erreur interne du serveur
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getInvoiceById($id)
    {
        $this->invoicesModel->getInvoiceById($id);
    }

    public function getFirstFiveInvoices()
    {
        $this->invoicesModel->getFirstFiveInvoices();
    }

    public function createInvoice($data)
    {
        $this->invoicesModel->createInvoice($data);
    }

    public function updateInvoice($data)
    {
        $this->invoicesModel->updateInvoice($data);
    }

    public function deleteInvoice($id)
    {
        $this->invoicesModel->deleteInvoice($id);
    }
}