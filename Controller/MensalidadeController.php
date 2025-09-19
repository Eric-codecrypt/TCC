<?php
// Inclui o Model correspondente
require_once __DIR__.'/../Model/MensalidadeModel.php';

class MensalidadeController {
    // Propriedade para armazenar a instância do Model
    private $MensalidadeModel;

    /**
     * O construtor recebe a conexão PDO já pronta (Injeção de Dependência)
     * e a utiliza para criar o MensalidadeModel.
     */
    public function __construct($pdo) {
        $this->MensalidadeModel = new MensalidadeModel($pdo);
    }

    /**
     * Ação para listar todas as mensalidades.
     * Simplesmente repassa a chamada para o Model.
     */
    public function listAll() {
        return $this->MensalidadeModel->getAllMensalidade();
    }

    /**
     * Ação para listar as mensalidades atrasadas.
     * Repassa a chamada para o Model.
     */
    public function listOverdue() {
        return $this->MensalidadeModel->getOverdueMensalidade();
    }

    /**
     * Ação para marcar uma mensalidade como paga.
     * Recebe o id, repassa para o Model e lida com o redirecionamento.
     */
    public function pay($MensalidadeId) {
        // A lógica de negócio (redirecionamento) permanece no controller
        $success = $this->MensalidadeModel->markAsPaid($MensalidadeId);

        if ($success) {
            header('Location: ../views/MensalidadeView.php?status=paid_success');
            exit();
        } else {
            header('Location: ../views/MensalidadeView.php?status=paid_error');
            exit();
        }
    }

    public function newMensalidade(){
        // Data atual
        $dataAtual = new DateTime();
        // Adicionar 1 mês
        $dataAtual->modify('+1 month');
        
    }
}