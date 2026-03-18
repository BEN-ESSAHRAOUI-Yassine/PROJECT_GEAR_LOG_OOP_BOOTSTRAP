<?php
require_once "../app/Core/Database.php";

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function all() {
        return $this->pdo->query("SELECT * FROM categories")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}