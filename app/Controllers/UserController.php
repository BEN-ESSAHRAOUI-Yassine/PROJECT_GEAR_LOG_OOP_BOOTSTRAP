<?php
require_once "../app/Models/User.php";
require_once "../app/Core/Controller.php";

class UserController extends Controller {

    private function checkAdmin() {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
            die("Access denied");
        }
    }

    public function index() {
        $this->checkAdmin();

        $userModel = new User();
        $users = $userModel->all();

        require "../views/users/index.php";
    }

    public function create() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel = new User();

            $userModel->create([
                'u' => $_POST['username'],
                'e' => $_POST['email'],
                'p' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'r' => $_POST['role']
            ]);

            header("Location: index.php?action=users");
            exit;
        }

        require "../views/users/create.php";
    }

    public function edit() {
        $this->checkAdmin();

        $id = $_GET['id'];

        $userModel = new User();
        $user = $userModel->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel->update([
                'email' => $_POST['email'],
                'role'  => $_POST['role'],
                'id'    => $id
            ]);

            header("Location: index.php?action=users");
            exit;
        }

        require "../views/users/edit.php";
    }

    public function delete() {
        $this->checkAdmin();

        $id = $_GET['id'];

        $userModel = new User();
        $userModel->delete($id);

        header("Location: index.php?action=users");
        exit;
    }
}