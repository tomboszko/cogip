<?php

class InvoiceModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllInvoices() {
        $query = "SELECT * FROM invoices";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoiceById($id) {
        $query = "SELECT * FROM invoices WHERE id = :id";
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
}
