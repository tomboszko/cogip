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
        try {
            $this->db->beginTransaction();
            $query = "INSERT INTO companies (company_name, type_id) VALUES (:company_name, :type_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_name', $data['company_name']);
            $stmt->bindParam(':type_id', $data['type_id']);
            $stmt->execute();
            $companyId = $this->db->lastInsertId();
            $query = "INSERT INTO company_addresses (company_id, address_id) VALUES (:company_id, :address_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_id', $companyId);
            $stmt->bindParam(':address_id', $data['address_id']);
            $stmt->execute();
            $this->db->commit();
            return $companyId;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            return null;
        }
    }

    public function updateCompany($id, $data) {
        $query = "UPDATE companies SET company_name = :company_name WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->execute();
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
            INNER JOIN types ON companies.type_id = company_types.id 
            ORDER BY companies.id DESC 
            LIMIT 5
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}