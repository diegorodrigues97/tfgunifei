<?php

namespace Core;

use Core\Validation;

require_once('../config.php');

/*
|--------------------------------------------------------------------------
| @Class: File
|--------------------------------------------------------------------------
|
| @ClassFunction: Manager  all files
|
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|
*/

class File{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct(){

}

/*
|--------------------------------------------------------------------------
| @Function: verifyExtension
|--------------------------------------------------------------------------
| This function return the file type, ex: png, jpeg, pdf, odt ...
*/

public function extension($file){
	$path_parts = pathinfo($file);
    $extension = $path_parts['extension'];
    return $extension;
}

/*
|--------------------------------------------------------------------------
| @Function: getAddress
|--------------------------------------------------------------------------
| This function return folders of the apllication
*/

public function getAddress($name){
    $options = array('ROOT'=>ADS_ROOT, 'CORE'=>ADS_CORE, 'VIEWS'=>ADS_VIEWS, 'CLASSES'=>ADS_CLASSES, 'ADMINS'=>ADS_ADMINS, 'BACKUP'=>ADS_BACKUP, 'HOME'=>ADS_HOME, 'FILES'=>ADS_FILES, 'TEMPORARY'=>ADS_TEMPORARY, 'FILE_REQUEST'=>ADS_FILE_REQUEST, 'FILE_INDEX'=>ADS_FILE_INDEX, 'FILE_UP'=>ADS_FILE_UP);

    if(isset($options[$name]))
        return $options[$name];
    else
    return false;
}

/*
|--------------------------------------------------------------------------
| @Function: move
|--------------------------------------------------------------------------
| This function moves a file from one location to another.
*/

function move($moveFrom, $moveTo, $fileName){
    //Check whether file exists
    if(!file_exists($file))
        return array("error"=>true, "message"=>"File@move: File not found!");
    //Try move the file
    if(!copy($moveFrom, $moveTo . $fileName . '.' . $this->extension($moveFrom)))
        return array("error"=>true, "message"=>"File@move: Could not move file!");
    //If success, delete file
    unlink($moveFrom);
    return array("error"=>false, "message"=>"Arquivo movido com sucesso!", "fileName"=>"");
}

/*
|--------------------------------------------------------------------------
| @Function: generate
|--------------------------------------------------------------------------
| This function generate a file
*/

function generate($input = array()){
    //Input Validation
    $filter = array('folder', 'name', 'extension', 'content');
    $v = Validation::isValid($input, $filter, 'folder, name, extention');
    if($v['error'] || !$v['requiredFields'])
        return $v;
    //Generate the file
    $fileName = $input['folder'] . $input['name'] . '.' . $input['extension'];
    $content = $input['content'];
    if(file_put_content($fileName, $content))
        return array("error"=>false, "message"=>"");
    else
        return array("error"=>true, "message"=>"File@generate: Could not generate the file!");
}

/*
|--------------------------------------------------------------------------
| @Function: compare
|--------------------------------------------------------------------------
| This function compare files
*/

function compare($path_origin, $path_dest){
    if(!file_exists($path_origin)){
        return array("error"=>true, "message"=>"Arquivo de origem inválido!");
    }
    if(!file_exists($path_dest)){
        return array("error"=>true, "message"=>"Arquivo de destino inválido!");
    }

    $content_origin = file_get_contents($path_origin);
    $content_dest = file_get_contents($path_dest);

    if($content_origin != $content_dest){
        return array("error"=>false, "message"=>"File@compare: Arquivos possuem conteúdo diferente!", "igual"=>false, "contentDest"=>$content_dest);
    }
    return array("error"=>false, "message"=>"File@compare: Arquivos possuem o mesmo conteúdo!", "igual"=>true);
}

/*
|--------------------------------------------------------------------------
| @StaticFunction: getContent
|--------------------------------------------------------------------------
| This function generate/update view controller files
*/

static function getContent($fileName, $type){
    $options = array('VIEW', 'TEMPLATE');
    if(!in_array($type,$options))
        return false;
    //type = VIEW
    if($type == 'VIEW')
        $pathFile = ADS_VIEWS . $fileName . '.view.html';
    //type = TEMPLATES
    if($type == 'TEMPLATE')
        $pathFile = ADS_TEMPLATES . $fileName . '.template.html';

    if(!file_exists($pathFile))
        return false;
    else
    return file_get_contents($pathFile);
}

/*
|--------------------------------------------------------------------------
| @Function: userControl_path
|--------------------------------------------------------------------------
| This function control user files
|
*/

function userControl_path($platform, $folder = false)
{
	//Try get user
	try
	{
		$user = Application::getApp()->getUser();
	}
	catch(Exception $e)
	{
		return ['error'=>true, 'message'=>'File@userControl_path: Could not get User!'];
	}
  //Check if exists user folder
	if(!is_dir(ADS_USERS . $user->getId()))
	{
		mkdir(ADS_USERS . $user->getId(), 0777);
	}

	//Check if exists platform
	if(!is_dir(ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform))
	{
		mkdir(ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform, 0777);
	}

	//If folder is false
	if(!$folder)
	{
		$path = ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform . DIRECTORY_SEPARATOR;
		return ['error'=>false, 'message'=>'', 'path'=>$path];
	}

	//Check folder
	if(!is_dir(ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform . DIRECTORY_SEPARATOR . $folder))
	{
		mkdir(ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform . DIRECTORY_SEPARATOR . $folder, 0777);
	}

	$path = ADS_USERS . $user->getId() . DIRECTORY_SEPARATOR . $platform . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
	return ['error'=>false, 'message'=>'', 'path'=>$path];
}




}
