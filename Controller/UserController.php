<?php
require_once __DIR__.'/../Model/UserModel.php';

class UserController {
    private $UserModel;

    function __construct($pdo) {
        $this->UserModel = new UserModel($pdo);
    }

    function register($username, $email, $password) {
        return $this->UserModel->register($username, $email, $password);
    }

    public function login($username, $email, $password) {
        return $this->UserModel->login($username, $email, $password);
    }

    public function findById($id) {
        return $this->UserModel->findById($id); // Reutilizar método do modelo
    }

    public function update($id, $username, $email, $password = null) {
        return $this->UserModel->update($id, $username, $email, $password); // Reutilizar método do modelo
    }

    public function delete($id) {
        return $this->UserModel->delete($id); // Reutilizar método do modelo
    }
}
