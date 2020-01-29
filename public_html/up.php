<?php

require_once(realpath($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'config.php'));


if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Pasta com os arquivos temporários
    $_UP['pasta'] =  ABSOLUTE_REFERENCE_FILES_TEMPORARY;


// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES["arquivo"]["error"] != 0) {
        echo json_encode(array("error"=>true, "message"=>"Erro ao fazer upload do arquivo!"));
        exit();
    }

// Validação da extensão de arquivo
    $arquivo_up = $_FILES['arquivo']['name'];
    $ext = strrchr($_FILES['arquivo']['name'],'.');
    $permitidos = array(".txt",".zip",".pdf",".doc",".docx", ".odt",".jpg",".gif",".png",".mp3",".wav",".mp4",".avi",".wmv",".mpg");
    if(!in_array($ext,$permitidos)){
        echo json_encode(array("error"=>true,"message"=>"Extenção de arquivo não permitido!", "input"=>$ext));
        exit();
    }



    $nome_final = time()."$ext";

// Depois verifica se é possível mover o arquivo para a pasta escolhida
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
        echo json_encode(array("error"=>false, "message"=>"", "fileName"=>$nome_final)); 
        exit();  
    }else {
        echo json_encode(array("error"=>true, "message"=>"Erro ao tentar mover o arquivo para a pasta de dados temporários!"));
    }
}




?>