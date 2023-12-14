<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\ContactsModel;
use App\Utilities\Pagination;
use Exception;

class ContactsController extends Controller
{
    private $contactsModel;

    public function __construct()
    {
        $this->contactsModel = new ContactsModel();
    }

    public function getAllContacts()
    {
        try {
            // Récupérez les paramètres de pagination depuis l'URL
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

            // Créez une instance de Pagination
            $pagination = new Pagination($perPage, $page);

            // Utilisez le modèle pour récupérer tous les contacts paginés
            $results = $this->contactsModel->getAllContacts($pagination);

            // Envoyez les résultats au format JSON
            header('Content-Type: application/json');
            echo json_encode($results, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Gérez les erreurs ici
            http_response_code(500); // Code d'erreur interne du serveur
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getContactById($id)
    {
        $this->contactsModel->getContactById($id);
    }

    public function getFirstFiveContacts()
    {
        $this->contactsModel->getFirstFiveContacts();
    }

    public function createContact($data)
    {
        $this->contactsModel->createContact($data);
    }

    public function updateContact($data)
    {
        $this->contactsModel->updateContact($data);
    }

    public function deleteContact($id)
    {
        $this->contactsModel->deleteContact($id);
    }
}