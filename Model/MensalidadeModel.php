<?php

class MensalidadeModel {
    private $conn; // Armazena a conexão com o banco de dados
    private $table = 'Mensalidades';

    // Construtor recebe a conexão com o banco
    public function __construct($db) {
        $this->conn = $db;
    }

    public function createMensalidade($user_id, $data_vencimento, $valor_cobrado){
        $sql = "INSERT INTO mensalidades(user_id, data_vencimento, valor_cobrado) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $data_vencimento, $valor_cobrado]);
    }

    // Método para buscar todas as mensalidades (pendentes e pagas)
    public function getAllMensalidade() {
        // A consulta SQL une as tabelas para pegar o nome do usuário
        $query = 'SELECT 
                    m.id,
                    u.nome_completo,
                    m.data_vencimento,
                    m.valor_cobrado,
                    m.status_pagamento,
                    m.data_pagamento
                  FROM ' . $this->table . ' m
                  JOIN users u ON m.user_id = u.id
                  ORDER BY m.data_vencimento DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar apenas as mensalidades atrasadas
    public function getOverdueMensalidade() {
        // CURDATE() é uma função do SQL que retorna a data atual
        $query = 'SELECT 
                    m.id,
                    u.nome_completo,
                    m.data_vencimento,
                    m.valor_cobrado,
                    m.status_pagamento
                  FROM ' . $this->table . ' m
                  JOIN users u ON m.user_id = u.id
                  WHERE m.status_pagamento = "Pendente" AND m.data_vencimento < CURDATE()
                  ORDER BY m.data_vencimento ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para marcar uma mensalidade como paga
    public function markAsPaid($MensalidadeId) {
        $query = 'UPDATE ' . $this->table . '
                  SET
                    status_pagamento = "Pago",
                    data_pagamento = CURDATE()
                  WHERE
                    id = :MensalidadeId';

        $stmt = $this->conn->prepare($query);

        // Limpa e vincula o parâmetro para segurança (evita SQL Injection)
        $MensalidadeId = htmlspecialchars(strip_tags($MensalidadeId));
        $stmt->bindParam(':MensalidadeId', $MensalidadeId);

        // Executa e retorna true se bem-sucedido, false caso contrário
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function updateDate($id, $data){
        $query = "UPDATE Mensalidades SET data_vencimento = ?, status_pagamento = 'Atrasado' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$data, $id]);
    }

    public function markAsUnpaid($id){
        $query = "UPDATE Mensalidades SET status_pagamento = 'Pendente' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
    }
    public function markAsPaidRaw($id) {
        $query = "UPDATE Mensalidades SET status_pagamento = 'Pago' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
    }

    public function markAsLate($id){
        $query = "UPDATE Mensalidades SET status_pagamento = 'Atrasado' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
    }
}