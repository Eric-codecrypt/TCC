<?php
// Define o diretório de upload
$diretorio = "View/fotos_de_perfil/";
if(isset($diretorio_override)){
    $diretorio = $diretorio_override;
}
// Verifica se o diretório existe, se não existir, cria o diretório
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

// Pega as informações do arquivo usando o novo name "foto-perfil"

$arquivo = $imagem_arquivo['name'];
$caminho_temporario = $imagem_arquivo['tmp_name'];
$tamanho_arquivo = $imagem_arquivo['size'];

$extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));

// Define o caminho final do arquivo
$caminho_final = $diretorio . basename($_SESSION['user_id'].".png");
$nome_arquivo_fotoperfil = str_replace($diretorio,"",$caminho_final);
// Verifica se o arquivo é uma imagem e seu tamanho é permitido (5MB no exemplo)
$tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];
$extensao = strtolower(pathinfo($caminho_final, PATHINFO_EXTENSION));

$error_code = null;

if (in_array($extensao, $tipos_permitidos)) {
        if (move_uploaded_file($caminho_temporario, $caminho_final)) {
            // echo "Upload bem-sucedido! Imagem salva em: $caminho_final";
            echo "";
        } else {
            $error_code = "Erro ao fazer o upload do arquivo.";
        }
    } else {
    $error_code = "Tipo de arquivo não permitido. Somente JPG, JPEG, PNG e GIF são aceitos.";
}
?>