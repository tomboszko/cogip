<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseModel;
use App\Utilities\Pagination;
use PDO;
use Exception;

class InvoicesModel extends BaseModel
{
    public function getAllInvoices(Pagination $pagination)
    {
        try {
            $query = "SELECT invoices.*, companies.name AS company_name 
                FROM invoices 
                INNER JOIN companies ON invoices.id_company = companies.id 
                ORDER BY id DESC";

            $results = $this->paginate($query, [], $pagination->getCurrentPage(), $pagination->getItemsPerPage());

            $allInvoices = ['all_invoices' => $results];

            return $allInvoices;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getFirstFiveInvoices()
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT invoices.*, companies.name AS company_name 
            FROM invoices 
            INNER JOIN companies ON invoices.id_company = companies.id
            ORDER BY id DESC LIMIT 5");
            $stmt->execute();
            $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $invoices = array('Last_invoices' => $invoices);
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getInvoiceById($id)
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT invoices.*, companies.name AS company_name 
            FROM invoices 
            INNER JOIN companies ON invoices.id_company = companies.id 
            WHERE invoices.id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $invoices = array('invoice' => $invoices);
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getCompanyInvoices()
    {
        try {
            $stmt = $this->getConnection()->prepare("SELECT invoices.*, companies.name AS company_name 
            FROM invoices 
            INNER JOIN companies ON invoices.id_company = companies.id
            ORDER BY id DESC LIMIT 5");
            $stmt->execute();
            $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $invoices = array('Invoices' => $invoices);
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo $e->getMessage();
        }        
    }
}