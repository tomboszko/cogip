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

    public function updateInvoice($id, $data) {
        // Prepare SQL statement
        $query = "UPDATE invoices SET updated_at = NOW(), due_date = DATE_ADD(NOW(), INTERVAL 2 MONTH)";

        // Add fields to the query based on the keys present in $data
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'ref':
                    $query .= ", ref = :ref";
                    break;
                case 'id_company':
                    if (!is_int($value) && !ctype_digit($value)) {
                        throw new InvalidArgumentException("Invalid format for id_company: must be an integer");
                    }
                    $query .= ", id_company = :id_company";
                    break;
                case 'price':
                    if (!is_numeric($value)) {
                        throw new InvalidArgumentException("Invalid format for price: must be numeric");
                    }
                    $query .= ", price = :price";
                    break;
            }
        }

        $query .= " WHERE id = :id";

        $stmt = $this->db->prepare($query);

        // Bind parameters and execute statement
        try {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (isset($data['ref'])) {
                $stmt->bindParam(':ref', $data['ref']);
            }
            if (isset($data['id_company'])) {
                $stmt->bindParam(':id_company', $data['id_company'], PDO::PARAM_INT);
            }
            if (isset($data['price'])) {
                $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR); // Bind as string if decimal
            }
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
