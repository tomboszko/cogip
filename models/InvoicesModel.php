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
        // Validate input
        if (!isset($data['id_company']) || !is_numeric($data['id_company']) || intval($data['id_company']) != $data['id_company']) {
            throw new InvalidArgumentException("Invalid or missing id_company: must be an integer");
        }
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            throw new InvalidArgumentException("Invalid or missing price: must be numeric");
        }
        // Check if ref is provided and validate
        if (isset($data['ref'])) {
            if (!is_string($data['ref'])) {
                throw new InvalidArgumentException("Invalid format for ref: must be a string");
            }
            if (strlen($data['ref']) > 50) {
                throw new InvalidArgumentException("Invalid length for ref: must be 50 characters or less");
            }
        } else {
            $data['ref'] = null; // Set default value if ref is not provided
        }
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
        // Additional type/format validations
        if (!is_int($data['id_company']) && !ctype_digit($data['id_company'])) {
            throw new InvalidArgumentException("Invalid format for id_company: must be an integer");
        }
        if (!is_numeric($data['price'])) {
            throw new InvalidArgumentException("Invalid format for price: must be numeric");
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
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR); // Bind as string if decimal
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
            $query = "SELECT invoices.*, companies.name AS company_name, invoices.id_company AS company_id 
                FROM invoices 
                INNER JOIN companies ON invoices.id_company = companies.id 
                ORDER BY invoices.id DESC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
