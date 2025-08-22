<?php

class DuesModel {
    private $conn; // Armazena a conexão com o banco de dados
    private $table = 'Mensalidades';

    // Construtor recebe a conexão com o banco
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para buscar todas as mensalidades (pendentes e pagas)
    public function getAllDues() {
        // A consulta SQL une as tabelas para pegar o nome do usuário
        $query = 'SELECT 
                    m.ID,
                    u.NomeCompleto,
                    m.DataVencimento,
                    m.ValorCobrado,
                    m.StatusPagamento,
                    m.DataPagamento
                  FROM ' . $this->table . ' m
                  JOIN users u ON m.UserID = u.id
                  ORDER BY m.DataVencimento DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar apenas as mensalidades atrasadas
    public function getOverdueDues() {
        // CURDATE() é uma função do SQL que retorna a data atual
        $query = 'SELECT 
                    m.ID,
                    u.NomeCompleto,
                    m.DataVencimento,
                    m.ValorCobrado,
                    m.StatusPagamento
                  FROM ' . $this->table . ' m
                  JOIN users u ON m.UserID = u.id
                  WHERE m.StatusPagamento = "Pendente" AND m.DataVencimento < CURDATE()
                  ORDER BY m.DataVencimento ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para marcar uma mensalidade como paga
    public function markAsPaid($duesId) {
        $query = 'UPDATE ' . $this->table . '
                  SET
                    StatusPagamento = "Pago",
                    DataPagamento = CURDATE()
                  WHERE
                    ID = :duesId';

        $stmt = $this->conn->prepare($query);

        // Limpa e vincula o parâmetro para segurança (evita SQL Injection)
        $duesId = htmlspecialchars(strip_tags($duesId));
        $stmt->bindParam(':duesId', $duesId);

        // Executa e retorna true se bem-sucedido, false caso contrário
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}