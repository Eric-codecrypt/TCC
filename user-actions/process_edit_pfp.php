<?php
include_once __DIR__."/../Controller/UserController.php";
include_once __DIR__."/../config.php";
session_start();
$Controller = new UserController($pdo);

$nome_inteiro = $_POST['nome_completo'];
$email = $_POST['email'];
$CPF = $_POST['CPF'];
$celular = $_POST['celular'];


$contaEmail = $Controller->listarContaPorEmail($email);


$emailValid = $contaEmail['id'] == intval($_SESSION['user_id']);
if(!$emailValid){
    $emailValid = empty($contaEmail);
}

if($emailValid){
        
    $imagem_arquivo = $_FILES['foto_perfil'];
    if($imagem_arquivo['name'] != ''){
        $diretorio_override = "../View/IMG/pfps/";
        include __DIR__.'/../upload-image.php';
            
        $error_code = 0;

        if($error_code == null){
            $Controller->updateFotoPerfil($_SESSION['user_id'],$nome_arquivo_fotoperfil);
            var_dump($nome_arquivo_fotoperfil);
        }

        $Controller->updateFotoPerfil($_SESSION['user_id'], $nome_arquivo_fotoperfil);
    }    
    $Controller->updateInfo(
        $_SESSION['user_id'],
        $nome_inteiro,
        $email,
        $CPF,
        $celular
    );
    
    header('Location: ../View/EditUser.php');
    
}else{
    $edit_perfil_error_code = 'Este Email já está sendo usado, tente novamente.';
    
    setcookie("edit_perfil_error_code",$edit_perfil_error_code, time()+5, "/");
    header('Location: ../View/EditUser.php');
}

?>