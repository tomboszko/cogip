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
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContacts($data) {
        $query = "INSERT INTO Contacts (name, company_id, email, created_at, phone, updated_at) VALUES (:company_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
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

    // Get the last 2 Contacts
    public function getLastContacts() {
        $query = "SELECT * FROM contacts ORDER BY id DESC LIMIT 2";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
