<?php

require_once "../app/Controllers/AssetController.php";
require_once "../app/Controllers/AuthController.php";
require_once "../app/Controllers/UserController.php";

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    //  AUTH
    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: index.php?action=login");
        break;

    //  ASSETS
    case 'create':
        (new AssetController())->create();
        break;

    case 'edit':
        (new AssetController())->edit();
        break;

    case 'delete':
        (new AssetController())->delete();
        break;

    //  USERS
    case 'users':
        (new UserController())->index();
        break;

    case 'createUser':
        (new UserController())->create();
        break;

    case 'editUser':
        (new UserController())->edit();
        break;

    case 'deleteUser':
        (new UserController())->delete();
        break;

    //  DEFAULT
    default:
        (new AssetController())->index();
        break;
}