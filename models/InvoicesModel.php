<?php

class InvoiceModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllInvoices($page) {
        // Ensure page is within a valid range
        $page = max(1, min($page, PHP_INT_MAX));

        $itemsPerPage = 5; // Set items per page to 5

        $offset = ($page - 1) * $itemsPerPage;

        $query = "SELECT invoices.*, companies.name AS company_name 
          FROM invoices 
          INNER JOIN companies ON invoices.id_company = companies.id 
          ORDER BY invoices.id 
          LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $invoicesData = [];
        foreach ($invoices as $invoice) {
          
            $invoicesData[] = $invoice;
        }

        $query = "SELECT COUNT(*) FROM invoices";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $totalInvoices = $stmt->fetchColumn();

        $totalPages = ceil($totalInvoices / $itemsPerPage);
        return [
            'pagination' => [
                'currentPage' => $page,
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalInvoices,
                'totalPages' => $totalPages
            ],
            'invoices' => $invoicesData
        ];
    }
    
    public function getInvoiceById($id) {
        $query = "SELECT invoices.*, companies.name AS company_name 
          FROM invoices 
          INNER JOIN companies ON invoices.id_company = companies.id 
          WHERE invoices.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createInvoice($data) {
        $query = "INSERT INTO invoices (ref, id_company, created_at, updated_at) VALUES (:ref, :id_company, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ref', $data['ref']);
        $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateInvoice($id, $data) {
        $query = "UPDATE invoices SET ref = :ref, id_company = :id_company, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ref', $data['ref']);
        $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteInvoice($id) {
        $query = "DELETE FROM invoices WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Get the last 2 invoices
    public function getLastInvoices() {
        $query = "SELECT invoices.*, companies.name AS company_name 
          FROM invoices 
          INNER JOIN companies ON invoices.id_company = companies.id 
          ORDER BY invoices.id DESC 
          LIMIT 2";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
