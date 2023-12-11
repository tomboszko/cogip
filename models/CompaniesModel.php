<?php

class CompanyModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllCompanies(Pagination $pagination) {
        // Calculating the offset for SQL query based on the current page and items per page
        $offset = $pagination->getOffset();
        $itemsPerPage = $pagination->getItemsPerPage();
        
        $query = "SELECT companies.*, types.name AS type_name 
        FROM companies 
        INNER JOIN types ON companies.type_id = types.id
        LIMIT :limit OFFSET :offset";
       

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        // Fetching the companies data
        $companiesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Query to count the total number of companies
        $queryTotal = "SELECT COUNT(*) FROM companies";
        $stmtTotal = $this->db->prepare($queryTotal);
        $stmtTotal->execute();
        $totalCompanies = $stmtTotal->fetchColumn();

        // Calculating the total number of pages
        $totalPages = ceil($totalCompanies / $itemsPerPage);

        // Check if the current page is greater than the total number of pages
        if ($pagination->getCurrentPage() > $totalPages) {
            return ['message' => "Page doesn't exist"];
        }

        // Returning the companies data along with pagination information
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
        $query = "SELECT companies.*, types.name AS type_name 
          FROM companies 
          INNER JOIN types ON companies.type_id = types.id 
          WHERE companies.id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCompany($data) {
        $query = "INSERT INTO companies (company_name) VALUES (:company_name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->execute();
        return $this->db->lastInsertId();
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
}