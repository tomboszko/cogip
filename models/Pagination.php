<?php

declare(strict_types=1);

class Pagination {
    private $itemsPerPage;
    private $currentPage;

    public function __construct($itemsPerPage, $currentPage) {
        $this->itemsPerPage = max(1, $itemsPerPage); // At least 1 item per page
        $this->currentPage = max(1, $currentPage); // Start from page 1
    }

    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }
}