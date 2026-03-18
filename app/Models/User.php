<?php
require_once "../app/Core/Database.php";

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM User_db WHERE username=:username"
        );
        $stmt->execute(['username'=>$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all() {
        return $this->pdo->query("SELECT * FROM User_db")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO User_db(username,email,password,his_role)
             VALUES(:u,:e,:p,:r)"
        );
        return $stmt->execute($data);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM User_db WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $stmt = $this->pdo->prepare(
            "UPDATE User_db SET email=:email, his_role=:role WHERE id=:id"
        );
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM User_db WHERE id=:id");
        return $stmt->execute(['id'=>$id]);
    }
}