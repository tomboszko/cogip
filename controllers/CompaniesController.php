<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\CompaniesModel;
use App\Utilities\Pagination;
use PDO;
use Exception;

class CompaniesController extends Controller
{
    private $companiesModel;

    public function __construct()
    {
        // Instanciez CompaniesModel directement dans le constructeur
        $this->companiesModel = new CompaniesModel();
    }

    public function getAllCompanies()
    {
        try {
            // Récupérez les paramètres de pagination depuis l'URL
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

            // Créez une instance de Pagination
            $pagination = new Pagination($perPage, $page);

            // Utilisez le modèle pour récupérer les entreprises paginées
            $results = $this->companiesModel->getAllCompanies($pagination);

            // Envoyez les résultats au format JSON
            header('Content-Type: application/json');
            echo json_encode($results, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Gérez les erreurs ici
            http_response_code(500); // Code d'erreur interne du serveur
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getCompanyById($id)
    {
        $this->companiesModel->getCompanyById($id);
    }
    
    public function getFirstFiveCompanies()
    {
        $this->companiesModel->getFirstFiveCompanies();
    }
    
    public function createCompany($data)
    {
        $this->companiesModel->createCompany($data);
    }

    public function updateCompany($data)
    {
        $this->companiesModel->updateCompany($data);
    }

    public function deleteCompany($id)
    {
        $this->companiesModel->deleteCompany($id);
    }
}
