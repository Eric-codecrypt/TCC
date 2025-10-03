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

    public function newMensalidade($id_user, $valor_cobrado){
        // Data atual
        $data_vencimento = new DateTime();
        // Adicionar 1 mês
        $data_vencimento->modify('+1 month');

        $this->MensalidadeModel->createMensalidade($id_user,$data_vencimento->format("Y-m-d"),$valor_cobrado);
        
        return $this->listAll()[0]['id'];
    }

    public function updateMensalidadeDate($id){
        // Data atual
        $data_vencimento = new DateTime();
        // Adicionar 1 mês
        $data_vencimento->modify('+1 month');

        $this->MensalidadeModel->updateDate($id, $data_vencimento->format("Y-m-d"));
    }

    public function updateAllMensalidades(){
        $all = $this->listAll();
        $data_agora = new DateTime();
        foreach($all as $mensalidade){
            $data_vencimento = new DateTime($mensalidade['data_vencimento']);
            if($mensalidade['data_pagamento'] != null){
                $data_pagamento = new DateTime($mensalidade['data_pagamento']);
            }else{
                $data_pagamento = null;
            }
            $data_criacao = new DateTIme($data_vencimento->format("Y-m-d"));
            $data_criacao->modify('-1 month');
            
            // data do vencimento já passou 
            if($data_vencimento < $data_agora){
                // Não foi pago nunca 
                if($data_pagamento == null){
                    $this->MensalidadeModel->markAsLate($mensalidade['id']);
                }else{ // já foi pago uma vez antes
                    
                    if($mensalidade['status_pagamento'] == 'Pago'){
                        $this->updateMensalidadeDate($mensalidade['id']);
                    }
                }
                
            }else{
                // já foi pago antes mas em uma mensalidade diferente
                if($data_pagamento < $data_criacao){
                    $this->MensalidadeModel->markAsUnpaid($mensalidade['id']);
                }

                // se a mensalidade foi paga mas nao foi registrada como pago
                if(($data_criacao < $data_pagamento AND $data_pagamento < $data_vencimento)){
                    var_dump('adfdsa');
                    $this->MensalidadeModel->markAsPaidRaw($mensalidade['id']);
                }
            }
            
        }
    }
}
