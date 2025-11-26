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
    public function updateInfo($id,$nome_inteiro, $email,$cpf,$cell){
        $sql = "UPDATE users SET email = ?, cpf = ?, nome_completo = ?, celular = ? WHERE id = ?";
        $params = [$email, $cpf, $nome_inteiro,$cell, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function updatePlanoInfo($id,$plan_id, $mensalidade_id){
        $sql = "UPDATE users SET plano_id = ?, mensalidade_id = ? WHERE id = ?";
        $params = [$plan_id, $mensalidade_id, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function updateMensalidadeInfo($id,$mensalidade_id){
        $sql = "UPDATE users SET  mensalidade_id = ? WHERE id = ?";
        $params = [$mensalidade_id, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function updateRenovarPlano($id,$renovar){
        $sql = "UPDATE users SET  renovar_plano = ? WHERE id = ?";
        $params = [$renovar, $id];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function listarContaPorEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        if (empty($result)){
            return [];
        }else{
            return $result[0];
        }
    }
    public function updateFotoPerfil($id_user,$nome_arquivo_fotoperfil){
        $sql = "UPDATE users SET nome_arquivo_fotoperfil = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nome_arquivo_fotoperfil,$id_user]);
    }

    // Cria um usuário a partir de dados do Google ou retorna o existente
    public function createOrGetFromGoogle($name, $email)
    {
        // 1) Tenta localizar pelo e-mail
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        }

        // 2) Não existe: cria com nome e email; gera senha aleatória (hash)
        $generatedPassword = bin2hex(random_bytes(16));
        $hashed = password_hash($generatedPassword, PASSWORD_DEFAULT);

        // Nome fallback: parte antes do @ se name vier vazio
        $nome = $name ?: (strpos($email, '@') !== false ? substr($email, 0, strpos($email, '@')) : $email);

        $sql = "INSERT INTO users (nome_completo, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nome, $email, $hashed]);

        // 3) Retorna o usuário recém-criado
        $id = $this->pdo->lastInsertId();
        return $this->findById($id);
    }
}
