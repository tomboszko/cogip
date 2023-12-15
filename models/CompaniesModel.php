<?php

class CompanyModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllCompanies(Pagination $pagination) {
        //calculating the offset
        $offset = $pagination->getOffset();
        $itemsPerPage = $pagination->getItemsPerPage();
        //query to fetch companies with type names, ordered by company ID
        $query = "SELECT companies.*, types.name AS type_name 
                  FROM companies 
                  INNER JOIN types ON companies.type_id = types.id 
                  ORDER BY companies.id 
                  LIMIT :limit OFFSET :offset";
        //prepare and execute the query with bound parameters
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        //fetching the companies data
        $companiesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //query to count the total number of companies
        $queryTotal = "SELECT COUNT(*) FROM companies";
        $stmtTotal = $this->db->prepare($queryTotal);
        $stmtTotal->execute();
        $totalCompanies = $stmtTotal->fetchColumn();
        //calculating the total number of pages
        $totalPages = ceil($totalCompanies / $itemsPerPage);
        //check if the current page is greater than the total number of pages
        if ($pagination->getCurrentPage() > $totalPages) {
            return ['message' => "Page doesn't exist"];
        }
        //returning the companies data along with pagination information
        return [
            'pagination' => [
                'currentPage' => $pagination->getCurrentPage(),
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalCompanies,
                'totalPages' => $totalPages
            ],
            'companies' => $companiesData
        ];
    }

    public function getCompanyById($id) {
        // Fetch the company and its type
        $query = "SELECT companies.*, types.name AS type_name 
                  FROM companies 
                  INNER JOIN types ON companies.type_id = types.id 
                  WHERE companies.id = :id";
        // prepare query statement
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$company) {
            return null; 
        }
        return $company;
    }

    
    
    public function createCompany($data) {
        // Validate input data
        if (!isset($data['name']) || !is_string($data['name'])) {
            throw new InvalidArgumentException("Invalid or missing company name");
        }
        if (!isset($data['type_id']) || !is_numeric($data['type_id'])) {
            throw new InvalidArgumentException("Invalid or missing type_id");
        }
        if (!isset($data['country']) || !is_string($data['country'])) {
            throw new InvalidArgumentException("Invalid or missing country");
        }
        if (!isset($data['tva']) || !is_string($data['tva'])) {
            throw new InvalidArgumentException("Invalid or missing tva");
        }
    
        try {
            $this->db->beginTransaction();
    
            // Insert into companies table
            $query = "INSERT INTO companies (name, type_id, country, tva, created_at, updated_at) 
                      VALUES (:name, :type_id, :country, :tva, NOW(), NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':type_id', $data['type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':country', $data['country']);
            $stmt->bindParam(':tva', $data['tva']);
            $stmt->execute();
            $companyId = $this->db->lastInsertId();
    
            $this->db->commit();
            return $companyId;
    
        } catch (PDOException $e) {
            $this->db->rollBack();
            // Rethrow the exception with a custom message
            throw new Exception("Error creating company: " . $e->getMessage());
        }
    }
    
    
    

    public function updateCompany($id, $data) {
        // validate input
        foreach (['name', 'type_id', 'country', 'tva'] as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("Missing required key in data: $key");
            }
        }

        // Validate input data
        if (!isset($data['name']) || !is_string($data['name'])) {
            throw new InvalidArgumentException("Invalid or missing company name");
        }
        if (!isset($data['type_id']) || !is_numeric($data['type_id'])) {
            throw new InvalidArgumentException("Invalid or missing type_id");
        }
        if (!isset($data['country']) || !is_string($data['country'])) {
            throw new InvalidArgumentException("Invalid or missing country");
        }
        if (!isset($data['tva']) || !is_string($data['tva'])) {
            throw new InvalidArgumentException("Invalid or missing tva");
        }
    
        //prepare sql statement
        $query = "
            UPDATE companies 
            SET name = :name, 
                type_id = :type_id, 
                country = :country, 
                tva = :tva, 
                updated_at = NOW() 
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($query);
        //bind parameters and execute statement
        try {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':type_id', $data['type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':country', $data['country']);
            $stmt->bindParam(':tva', $data['tva']);
            $stmt->execute();
        } catch (PDOException $e) {
            //handle exception
            throw new Exception("Database error: " . $e->getMessage());
        }
        //return the number of affected rows
        return $stmt->rowCount();
    }
        

    public function deleteCompany($id) {
        $query = "DELETE FROM companies WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }       
    
    public function getLastCompanies() {
        $query = "
            SELECT companies.*, types.name AS type_name 
            FROM companies 
            INNER JOIN types ON companies.type_id = types.id 
            ORDER BY companies.id DESC 
            LIMIT 5
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}