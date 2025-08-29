<?php
// Inclui o Model correspondente
require_once __DIR__.'/../Model/DuesModel.php';

class DuesController {
    // Propriedade para armazenar a instância do Model
    private $duesModel;

    /**
     * O construtor recebe a conexão PDO já pronta (Injeção de Dependência)
     * e a utiliza para criar o DuesModel.
     */
    public function __construct($pdo) {
        $this->duesModel = new DuesModel($pdo);
    }

    /**
     * Ação para listar todas as mensalidades.
     * Simplesmente repassa a chamada para o Model.
     */
    public function listAll() {
        return $this->duesModel->getAllDues();
    }

    /**
     * Ação para listar as mensalidades atrasadas.
     * Repassa a chamada para o Model.
     */
    public function listOverdue() {
        return $this->duesModel->getOverdueDues();
    }

    /**
     * Ação para marcar uma mensalidade como paga.
     * Recebe o id, repassa para o Model e lida com o redirecionamento.
     */
    public function pay($duesId) {
        // A lógica de negócio (redirecionamento) permanece no controller
        $success = $this->duesModel->markAsPaid($duesId);

        if ($success) {
            header('Location: ../views/DuesView.php?status=paid_success');
            exit();
        } else {
            header('Location: ../views/DuesView.php?status=paid_error');
            exit();
        }
    }
}