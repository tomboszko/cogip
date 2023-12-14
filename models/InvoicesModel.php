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

    /**
     * Create a new invoice 
     *
     * @param array $data An array containing the invoice data. Must include 'ref', 'id_company', and 'price'.
     * @return int The ID of the newly created invoice.
     * @throws InvalidArgumentException If a required key is missing from $data.
     * @throws PDOException If an error occurs while executing the SQL statement.
     */
    public function createInvoice($data) {
        // Validate input
        // foreach (['ref', 'id_company', 'price'] as $key) {
        //     if (!isset($data[$key])) {
        //         throw new InvalidArgumentException("Missing required key in data: $key");
        //     }
        // }
        // Prepare SQL statement
        $query = "INSERT INTO invoices (ref, id_company, created_at, updated_at, due_date, price) 
                  VALUES (:ref, :id_company, NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 2 MONTH), :price)";
        $stmt = $this->db->prepare($query);
        // Bind parameters and execute statement
        try {
            $stmt->bindParam(':ref', $data['ref']);
            $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price']);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle exception 
            throw $e;
        }
        // Return the ID of the newly created invoice
        return $this->db->lastInsertId();
    }
    
    public function updateInvoice($id, $data) {
        // Validate input
        foreach (['ref', 'id_company', 'price'] as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("Missing required key in data: $key");
            }
        }
        // Prepare SQL statement
        $query = "
            UPDATE invoices 
            SET ref = :ref, 
                id_company = :id_company, 
                updated_at = NOW(), 
                due_date = DATE_ADD(NOW(), INTERVAL 2 MONTH), 
                price = :price 
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($query);

        // Bind parameters and execute statement
        try {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':ref', $data['ref']);
            $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $data['price']);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle exception 
            throw new Exception("Database error: " . $e->getMessage());
        }
        // Return the number of affected rows
        return $stmt->rowCount();
    }
    

    public function deleteInvoice($id) {
        $query = "DELETE FROM invoices WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getLastInvoices() {
        $query = "SELECT invoices.*, companies.name AS company_name 
          FROM invoices 
          INNER JOIN companies ON invoices.id_company = companies_id 
          ORDER BY invoices.id DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
