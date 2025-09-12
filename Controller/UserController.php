<?php
require_once __DIR__.'/../Model/UserModel.php';

class UserController {
    private $UserModel;

    function __construct($pdo) {
        $this->UserModel = new UserModel($pdo);
    }

    function register($nome_completo, $email, $password) {
        return $this->UserModel->register($nome_completo, $email, $password);
    }

    public function login($email, $password)
    {
        return $this->UserModel->login($email, $password);
    }

    public function findById($id) {
        return $this->UserModel->findById($id); // Reutilizar método do modelo
    }

    public function update($id, $username, $email, $password = null) {
        return $this->UserModel->update($id, $username, $email, $password); // Reutilizar método do modelo
    }
    public function updatePersonalInfo($id,$cpf,$cell,$info){
        return $this->UserModel->updatePersonalInfo($id,$cpf,$cell, $info); // Reutilizar método do modelo
    }

    public function delete($id) {
        return $this->UserModel->delete($id); // Reutilizar método do modelo
    }
}
