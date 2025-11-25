<?php
// Inclui o Model correspondente
require_once __DIR__.'/../Model/MensalidadeModel.php';
require_once __DIR__.'/UserController.php';

class MensalidadeController {
    // Propriedade para armazenar a instância do Model
    
    private $MensalidadeModel;
    private $UserController;
    private $pdo;

    /**
     * O construtor recebe a conexão PDO já pronta (Injeção de Dependência)
     * e a utiliza para criar o MensalidadeModel.
     */
    public function __construct($pdo) {
        $this->MensalidadeModel = new MensalidadeModel($pdo);
        $this->UserController = new UserController($pdo);
        $this->pdo = $pdo;
    }

    /**
     * Ação para listar todas as mensalidades.
     * Simplesmente repassa a chamada para o Model.
     */
    public function listAll() {
        return $this->MensalidadeModel->getAllMensalidade();
    }
    public function listbyID($id) {
        return $this->MensalidadeModel->getAllMensalidadeporId($id);
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
    public function payUnAdmin($MensalidadeId) {
        $this->MensalidadeModel->markAsPaid($MensalidadeId);
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

    public function createNewAndAssignOldUser($id_mensalidade){
        $old = $this->listbyID($id_mensalidade);
        $userMensalidade = $this->UserController->findById($old['user_id']);
        if($userMensalidade['mensalidade_id'] == $old['id'] && $userMensalidade["renovar_plano"] == "Sim"){
            $this->newMensalidade($old['user_id'],$old['valor_cobrado']);
            $newid = $this->listAll()[0]['id'];
            $this->UserController->updateMensalidadeInfo($old['user_id'],$newid);
        }
    }

    public function cancelarPlano($mensalidade_id){
        $mensalidade = $this->MensalidadeModel->getAllMensalidadeporId($mensalidade_id);
        $id_user = $mensalidade['user_id'];
        
        $this->UserController->updatePlanoInfo($id_user, NULL, NULL);
        $sql = "DELETE FROM mensalidades WHERE id = $mensalidade_id";
        var_dump($mensalidade);
        $this->pdo->prepare($sql)->execute();

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
                        $this->createNewAndAssignOldUser($mensalidade['id']);
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
