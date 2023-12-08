<?php

class InvoiceModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllInvoices(Pagination $pagination) {
        // Calculating the offset for SQL query based on the current page and items per page
        $offset = $pagination->getOffset();
        $itemsPerPage = $pagination->getItemsPerPage();

        // SQL query to fetch invoices with company names, ordered by invoice ID
        $query = "SELECT invoices.*, companies.name AS company_name 
                  FROM invoices 
                  INNER JOIN companies ON invoices.id_company = companies.id 
                  ORDER BY invoices.id 
                  LIMIT :limit OFFSET :offset";

        // Preparing and executing the SQL query with bound parameters
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Fetching the invoices data
        $invoicesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query to count the total number of invoices
        $queryTotal = "SELECT COUNT(*) FROM invoices";
        $stmtTotal = $this->db->prepare($queryTotal);
        $stmtTotal->execute();
        $totalInvoices = $stmtTotal->fetchColumn();

        // Calculating the total number of pages
        $totalPages = ceil($totalInvoices / $itemsPerPage);

        // Check if the current page is greater than the total number of pages
    if ($pagination->getCurrentPage() > $totalPages) {
        return ['message' => "Page doesn't exist"];
    }

        // Returning the invoices data along with pagination information
        return [
            'pagination' => [
                'currentPage' => $pagination->getCurrentPage(),
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
        $query = "INSERT INTO invoices (ref, id_company, created_at, updated_at, due_date) VALUES (:ref, :id_company, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ref', $data['ref']);
        $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateInvoice($id, $data) {
        $query = "UPDATE invoices SET ref = :ref, id_company = :id_company, updated_at, due_date = NOW() WHERE id = :id";
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
}
