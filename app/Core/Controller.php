<?php

class Controller {
    protected function view($path, $data = []) {
        extract($data);
        require "../views/$path.php";
    }
}