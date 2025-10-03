<?php
class UserModel
{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function register($nome_completo, $email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($results)) {
            $sql = "INSERT INTO users(nome_completo, email, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nome_completo, $email, $password]);
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password)
    {
        // Primeiro busque o usuário pelo email (ou nome_completo se preferir)
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Depois verifique se a senha corresponde com password_verify
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $nome_completo, $email, $hashedPassword = null)
    {
        $sql = "UPDATE users SET nome_completo = ?, email = ?";
        $params = [$nome_completo, $email];

        if ($hashedPassword) {
            $sql .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function updatePersonalInfo($id,$cpf,$cell,$info){
        $sql = "UPDATE users SET cpf = ?, celular = ?, info_treinamento = ? WHERE id = ?";
        $params = [$cpf, $cell, $info, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function updatePlanoInfo($id,$plan_id, $mensalidade_id){
        $sql = "UPDATE users SET plano_id = ?, mensalidade_id = ? WHERE id = ?";
        $params = [$plan_id, $mensalidade_id, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
