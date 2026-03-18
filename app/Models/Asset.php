<?php
require_once "../app/Core/Database.php";

class Asset {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll($search, $category, $sort, $order, $limit, $offset) {

        $allowedSort = ['device_name','price','status','serial_number'];
        if (!in_array($sort,$allowedSort)) $sort = 'device_name';
        $order = ($order === 'DESC') ? 'DESC' : 'ASC';

        $sql = "SELECT assets.*, categories.name AS category_name
                FROM assets
                INNER JOIN categories ON assets.category_id = categories.id
                WHERE 1";

        $params = [];

        if ($search !== '') {
            $sql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
            $params['search'] = "%$search%";
        }

        if ($category !== '') {
            $sql .= " AND category_id = :category";
            $params['category'] = $category;
        }

        $sql .= " ORDER BY $sort $order LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }

        $stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
        $stmt->bindValue(':offset',$offset,PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search,$category) {
        $sql = "SELECT COUNT(*) FROM assets WHERE 1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
            $params['search'] = "%$search%";
        }

        if ($category !== '') {
            $sql .= " AND category_id = :category";
            $params['category'] = $category;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function totalValue() {
        return $this->pdo->query("SELECT SUM(price) FROM assets")->fetchColumn();
    }

    public function filteredValue($search,$category) {
        $sql = "SELECT SUM(price) FROM assets WHERE 1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
            $params['search'] = "%$search%";
        }

        if ($category !== '') {
            $sql .= " AND category_id = :category";
            $params['category'] = $category;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function countAll() {
        return $this->pdo->query("SELECT COUNT(*) FROM assets")->fetchColumn();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM assets WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO assets(serial_number,device_name,price,status,category_id)
             VALUES(?,?,?,?,?)"
        );
        return $stmt->execute($data);
    }

    public function update($data,$id) {
        $stmt = $this->pdo->prepare(
            "UPDATE assets
             SET serial_number=?,device_name=?,price=?,status=?,category_id=?
             WHERE id=?"
        );
        return $stmt->execute([...$data,$id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM assets WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function serialExists($serial,$id=null) {
        if($id){
            $stmt=$this->pdo->prepare(
                "SELECT id FROM assets WHERE serial_number=? AND id!=?"
            );
            $stmt->execute([$serial,$id]);
        } else {
            $stmt=$this->pdo->prepare(
                "SELECT id FROM assets WHERE serial_number=?"
            );
            $stmt->execute([$serial]);
        }
        return $stmt->fetch();
    }
}