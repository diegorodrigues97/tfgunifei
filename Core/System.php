<?php

namespace Core;

require_once('../config.php');

use Core\Validation;
use Core\Database\Db as Db;

/*
|--------------------------------------------------------------------------
| @Class: System
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
|
*/

class System{

/*
|--------------------------------------------------------------------------
| @systemInit
|--------------------------------------------------------------------------
*/

function init_database()
{
  //Check Database Driver
  if(!isset(getAppConfig()['database']['driver']))
  {
    throw new \Exception("Invalid system default database configuration!", 102);
  }

  //Check if the system tables have been created in the database
  $db = new Db();
  return $db->loadSystemTables();
} 


/*
|--------------------------------------------------------------------------
| @systemIntegrity
|--------------------------------------------------------------------------
*/
public function systemIntegrity(){
    //Array de respodata
    $output = array();
    date_default_timezone_set('America/Sao_Paulo');
    //Obtém o path da pasta com os arquivos de backup
    $path_folder_backup = ABSOLUTE_REFERENCE_FOLDER_BACKUP;

    #Verifica a integridade do arquivo request.php
    $v = $this->compareFile($path_folder_backup . DIRECTORY_SEPARATOR . 'request.php', ABSOLUTE_REFERENCE_FILE_JAVASCRIPT_REQUEST);
    if($v['error']){
        return $v;
    }
    //Se possuirem conteúdos diferentes, apaga o arquivo e manda o backup no lugar
    if(!$v['igual']){
        //Captura o conteúdo do arquivo infectado
        $output['request_content'] = $v['contentDest'];
        unlink(ABSOLUTE_REFERENCE_FILE_JAVASCRIPT_REQUEST);
        $f1 = copy($path_folder_backup . DIRECTORY_SEPARATOR . 'request.php', ABSOLUTE_REFERENCE . 'inicio/request.php');
        if($f1){
            $output['request'] = 'Ameaça Detectada! Dados atualizados: '.Date("Y/n/d H:i:s");
        }else{
            $output['request'] = 'Ameaça Detectada! Erro ao fazer backup: '. Date("Y/n/d H:i:s");
        }
    }

    #Verifica a integridade do arquivo index.php
    $v = $this->compareFile($path_folder_backup . DIRECTORY_SEPARATOR . 'index.php', ABSOLUTE_REFERENCE_FILE_INDEX);
    if($v['error']){
        return $v;
    }
    //Se possuirem conteúdos diferentes, apaga o arquivo e manda o backup no lugar
    if(!$v['igual']){
        //Captura o conteúdo do arquivo infectado
        $output['index_content'] = $v['contentDest'];
        unlink(ABSOLUTE_REFERENCE_FILE_INDEX);
        $f1 = copy($path_folder_backup . DIRECTORY_SEPARATOR . 'index.php', ABSOLUTE_REFERENCE . 'inicio/index.php');
        if($f1){
            $output['index'] = 'Ameaça Detectada! Dados atualizados: '.Date("Y/n/d H:i:s");
        }else{
            $output['index'] = 'Ameaça Detectada! Erro ao fazer backup: '. Date("Y/n/d H:i:s");
        }
    }

     #Verifica a integridade do arquivo up.php
     $v = $this->compareFile($path_folder_backup . DIRECTORY_SEPARATOR . 'up.php', ABSOLUTE_REFERENCE_FILE_UP);
     if($v['error']){
         return $v;
     }
     //Se possuirem conteúdos diferentes, apaga o arquivo e manda o backup no lugar
     if(!$v['igual']){
         //Captura o conteúdo do arquivo infectado
         $output['up_content'] = $v['contentDest'];
         unlink(ABSOLUTE_REFERENCE_FILE_UP);
         $f1 = copy($path_folder_backup . DIRECTORY_SEPARATOR . 'up.php', ABSOLUTE_REFERENCE . 'inicio/up.php');
         if($f1){
             $output['up'] = 'Ameaça Detectada! Dados atualizados: '.Date("Y/n/d H:i:s");
         }else{
             $output['up'] = 'Ameaça Detectada! Erro ao fazer backup: '. Date("Y/n/d H:i:s");
         }
     }

    //Se houver atualizações, gera um alerta de segurança
     //Cola a mensagem na Stack de e-mails
     if(!empty($output)){
        $stackEmail = new StackEmail();
        $input = array("email_to"=>ALERT_SECURITY, "email_subject"=>"Integridade do Sistema Afetada", "email_message"=>json_encode($output), "email_date_task"=>date("Y-m-d H:i:s"), "email_detail"=>"");
        $alert = $stackEmail->new($input);
     }

    return $output;
}

}
