<?php
require_once "../app/Models/Asset.php";
require_once "../app/Models/Category.php";
require_once "../app/Core/Controller.php";

class AssetController extends Controller {

    private function checkAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    private function canEdit() {
        return $_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Technician';
    }

    // ✅ DASHBOARD (UNCHANGED LOGIC)
    public function index() {

        $this->checkAuth();

        $search   = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $sort     = $_GET['sort'] ?? 'device_name';
        $order    = $_GET['order'] ?? 'ASC';
        $page     = max(1,(int)($_GET['page'] ?? 1));

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $assetModel = new Asset();
        $categoryModel = new Category();

        $assets = $assetModel->getAll($search,$category,$sort,$order,$limit,$offset);

        $totalAssets = $assetModel->countFiltered($search,$category);
        $totalPages  = ceil($totalAssets / $limit);

        $totalValue = $assetModel->totalValue();
        $filteredValue = $assetModel->filteredValue($search,$category);
        $totalAssetsInventory = $assetModel->countAll();

        $categories = $categoryModel->all();

        require "../views/assets/index.php";
    }

    // ✅ CREATE (ADD ASSET)
    public function create() {

        $this->checkAuth();

        if (!$this->canEdit()) {
            die("Access denied");
        }

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $serial = trim($_POST['serial'] ?? '');
            $name   = trim($_POST['name'] ?? '');
            $price  = $_POST['price'] ?? '';
            $status = $_POST['status'] ?? '';
            $category = $_POST['category'] ?? '';

            // 🔒 VALIDATION (UNCHANGED)
            if ($serial === '') $errors[] = "Serial number is required";
            if ($name === '')   $errors[] = "Device name is required";
            if ($price === '' || !is_numeric($price)) $errors[] = "Price must be a valid number";
            if ($status === '') $errors[] = "Status is required";
            if ($category === '') $errors[] = "Category is required";

            $assetModel = new Asset();

            if (empty($errors)) {
                if ($assetModel->serialExists($serial)) {
                    $errors[] = "Serial number already exists.";
                }
            }

            if (empty($errors)) {
                $assetModel->create([
                    $serial,
                    $name,
                    $price,
                    $status,
                    $category
                ]);

                header("Location: index.php");
                exit();
            }
        }

        require "../views/assets/create.php";
    }

    // ✅ EDIT
    public function edit() {

        $this->checkAuth();

        if (!$this->canEdit()) {
            die("Access denied");
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php");
            exit();
        }

        $assetModel = new Asset();
        $categoryModel = new Category();

        $asset = $assetModel->find($id);

        if (!$asset) {
            die("Asset not found");
        }

        $categories = $categoryModel->all();
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $serial = trim($_POST['serial'] ?? '');
            $name   = trim($_POST['name'] ?? '');
            $price  = $_POST['price'] ?? '';
            $status = $_POST['status'] ?? '';
            $category = $_POST['category'] ?? '';

            // 🔒 SAME VALIDATION
            if ($serial === '') $errors[] = "Serial number is required";
            if ($name === '')   $errors[] = "Device name is required";
            if ($price === '' || !is_numeric($price)) $errors[] = "Price must be a valid number";
            if ($status === '') $errors[] = "Status is required";
            if ($category === '') $errors[] = "Category is required";

            if (empty($errors)) {
                if ($assetModel->serialExists($serial, $id)) {
                    $errors[] = "Serial number already exists.";
                }
            }

            if (empty($errors)) {

                $assetModel->update([
                    $serial,
                    $name,
                    $price,
                    $status,
                    $category
                ], $id);

                header("Location: index.php");
                exit();
            }
        }

        require "../views/assets/edit.php";
    }

    // ✅ DELETE
    public function delete() {

        $this->checkAuth();

        if (!$this->canEdit()) {
            die("Access denied");
        }

        if (isset($_GET['id'])) {

            $assetModel = new Asset();
            $assetModel->delete($_GET['id']);
        }

        header("Location: index.php");
        exit();
    }
}