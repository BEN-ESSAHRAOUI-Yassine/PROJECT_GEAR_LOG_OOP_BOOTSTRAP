<?php
require_once "../app/Models/User.php";
require_once "../app/Core/Controller.php";

class AuthController extends Controller {

    public function login() {
        session_start();
        $error = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userModel = new User();

            $user = $userModel->findByUsername($_POST['username']);

            if($user && password_verify($_POST['password'],$user['password'])){
                $_SESSION['user_id']=$user['id'];
                $_SESSION['username']=$user['username'];
                $_SESSION['role']=$user['his_role'];
                header("Location: index.php");
                exit;
            } else {
                $error="Invalid username or password";
            }
        }

        $this->view("auth/login", compact('error'));
    }
}