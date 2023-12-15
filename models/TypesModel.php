<?php

class TypeModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllTypes() {
        $query = "SELECT * FROM types";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $types;
    }
}