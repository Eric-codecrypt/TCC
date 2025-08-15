<?php
class UserModel
{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function register($username, $email, $password)
    {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $email]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($results)) {
            $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username, $email, $password]);
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $email, $password)
    {
        $sql = "SELECT * FROM users WHERE username = ? AND email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

    public function update($id, $username, $email, $hashedPassword = null)
    {
        $sql = "UPDATE users SET username = ?, email = ?";
        $params = [$username, $email];

        if ($hashedPassword) {
            $sql .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

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
