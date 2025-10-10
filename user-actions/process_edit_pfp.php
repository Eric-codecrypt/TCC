<?php
include_once __DIR__."/../Controller/UserController.php";
include_once __DIR__."/../config.php";
session_start();
$Controller = new UserController($pdo);


if(true){

        
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
    
        header('Location: ../View/UserView.php');
    }
}

?>