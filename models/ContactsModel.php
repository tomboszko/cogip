<?php

class ContactModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllContacts(Pagination $pagination) {
        // Calculating the offset for SQL query based on the current page and items per page
        $offset = $pagination->getOffset();
        $itemsPerPage = $pagination->getItemsPerPage();
        $query = "SELECT contacts.*, companies.name AS company_name 
              FROM contacts 
              INNER JOIN companies ON contacts.company_id = companies.id
              LIMIT :limit OFFSET :offset";

              // Preparing and executing the SQL query with bound parameters

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        // Fetching the contacts data
        $contactsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query to count the total number of contacts
        $queryTotal = "SELECT COUNT(*) FROM contacts";
        $stmtTotal = $this->db->prepare($queryTotal);
        $stmtTotal->execute();
        $totalContacts = $stmtTotal->fetchColumn();

        // Calculating the total number of pages
        $totalPages = ceil($totalContacts / $itemsPerPage);

        // Check if the current page is greater than the total number of pages
        if ($pagination->getCurrentPage() > $totalPages) {
            return ['message' => "Page doesn't exist"];
        }
        // Returning the contacts data along with pagination information
        return [
            'pagination' => [
                'currentPage' => $pagination->getCurrentPage(),
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalContacts,
                'totalPages' => $totalPages
            ],
            'contacts' => $contactsData
        ];
    }
    
    public function getContactById($id) {
        $query = "SELECT contacts.*, companies.name AS company_name 
        FROM contacts 
        INNER JOIN companies ON contacts.company_id = companies.id
                  WHERE contacts.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContacts($data) {
        // Validate input data
        if (!isset($data['name']) || !filter_var($data['name'])) {
            throw new InvalidArgumentException("Invalid or missing contact name");
        }
        if (!isset($data['company_id']) || !is_numeric($data['company_id'])) {
            throw new InvalidArgumentException("Invalid or missing company_id");
        }
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid or missing email");
        }
        if (!isset($data['phone']) || !is_string($data['phone'])) {
            throw new InvalidArgumentException("Invalid or missing phone number");
        }
    
        // Prepare SQL statement
        $query = "INSERT INTO contacts (name, company_id, email, phone, created_at, updated_at, Avatar) 
                  VALUES (:name, :company_id, :email, :phone, NOW(), NOW(), :Avatar)";
        $stmt = $this->db->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
    
        // Handle the Avatar field
        if (isset($data['Avatar']) && !empty($data['Avatar'])) {
            // If Avatar is provided, use it
            $stmt->bindParam(':Avatar', $data['Avatar']);
        } else {
            // If Avatar is not provided, use NULL to trigger the default value
            $Avatar = null;
            $stmt->bindParam(':Avatar', $Avatar, PDO::PARAM_NULL);
        }
    
        // Execute the query
        try {
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating contact: " . $e->getMessage());
        }
    }
    
    
    
       
    
    
    public function updateContact($id, $data) {
        $query = "UPDATE contacts SET name = :name, company_id = :company_id, email = :email, phone = :phone, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteContact($id) {
        $query = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }   

    public function getLastContacts() {
        $query = "
            SELECT contacts.*, companies.name AS company_name 
            FROM contacts 
            INNER JOIN companies ON contacts.company_id = companies.id 
            ORDER BY contacts.id DESC 
            LIMIT 5
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
