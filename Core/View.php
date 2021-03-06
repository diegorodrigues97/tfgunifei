<?php

namespace Core;

<<<<<<< HEAD
use Core\Validation;

require_once('../config.php');
=======
require_once('../config.php');

use Core\Validation;

use Core\ForeachDecoder;
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3

/*
|--------------------------------------------------------------------------
| @Class: View
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
| This class manger all views
|
*/

class View{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

//Page Title
protected $title = APP_NAME;
//html code
protected $html_code;
//code struct
protected $page_head = array();
protected $page_body = array();
//head tags
protected $tag_link = array();
protected $tag_script = array();
//javascrip tag
protected $tag_javaScript = array();
protected $tag_style = array();
protected $tag_meta = array();

<<<<<<< HEAD
=======
protected $render_php = true;

>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

<<<<<<< HEAD
function __construct($viewName, $vars = array()){
=======
function __construct($viewName, $vars = array(), $render_php = true){
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
    //Get the template defaul
    $code = File::getContent('default', 'TEMPLATE');
    if(!$code)
        throw new \Exception('View@construct: template default not found!');
    $this->html_code = $code;
    //Get view
    $code = File::getContent($viewName, 'VIEW');
    if(!$code)
        throw new \Exception('View@construct: view not found!');
    //Check if exists templates to add
    $code = $this->searchTemplateReference($code);
    //Check if exists controllers reference
    $code = $this->searchControllerReference($code);
<<<<<<< HEAD
=======
    //Check if exists foreach loop and process it

    $this->render_php = $render_php;

    if($render_php) {
        $code = $this->searchForeachReference($vars, $code);
    }
    else {

        $data = json_encode($vars);

        $script_code = "
            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    var data = {$data};
                    var framework = new Framework(data, document);
                })
            </script>
        ";

        array_push($this->tag_javaScript, $script_code);
    }
    

    
    //Check if exists conditional statements and process it
    $code = $this->searchConditionalReference($vars, $code);
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
    //Load vars
    if(!empty($vars)){
        $code = $this->loadVars($vars, $code);
    }
    $code = $this->headProcessing($code);
    $this->addBody($code);
}

/*
|--------------------------------------------------------------------------
| @Function: setTitle
|--------------------------------------------------------------------------
*/

function setTitle($title = null){
    $this->title = $title;
}

/*
|--------------------------------------------------------------------------
| @Function: render
|--------------------------------------------------------------------------
*/

function render($enableToken = true){
    //Add tags 'meta'
    $this->tag_meta = array_unique($this->tag_meta);
    foreach($this->tag_meta as $value){
        array_push($this->page_head, $value);
    }
    //Add link tag
    $this->tag_link = array_unique($this->tag_link);
    foreach($this->tag_link as $value){
        array_push($this->page_head, '<link rel="stylesheet" type="text/css" href="'.$value.'">');
    }
    //Add script tag
    $this->tag_javaScript =  array_unique($this->tag_javaScript);
    foreach($this->tag_javaScript as $value){
        array_push($this->page_head, $value);
    }
    //Add javascript code
    $this->tag_script =  array_unique($this->tag_script);
    foreach($this->tag_script as $value){
        array_push($this->page_head, '<script type="text/javascript" src="'.$value.'"></script>');
    }
    //Add style tag
    $this->tag_style = array_unique($this->tag_style);
    foreach($this->tag_style as $value){
        array_push($this->page_head, '<style type="text/css">'.$value.'</style>');
    }
    //Adciona title
    if(Application::getApp()->getSite()->getCurrentPage()!= false)
        $title = Application::getApp()->getSite()->getCurrentPage()->getTitle();
    else
        $title = false;

    if($this->title == APP_NAME && $title != false){
        $this->title = $title;
    }
    array_push($this->page_head, '<title>'.$this->title.'</title>');
    //Substitui variáveis de corpo e cabeçalho no template default.
    $this->html_code = $this->loadVars(array("body"=>implode($this->page_body), "head"=>implode($this->page_head)), $this->html_code);
    //Remove qualquer outra variável de modelo que não tenha sido substituída
    $this->html_code = $this->rmVar(null, $this->html_code);
    //Solicita Token a Aplication
    if(isset($_SESSION['app']) && $enableToken && preg_match('/<form/', $this->html_code)){
        $app = Application::getApp();
        $token = $app->getSecurity()->newToken();
        Application::setApp($app);
        $this->html_code = preg_replace('/<input type="hidden" name="token"(.*?)>/','<input type="hidden" name="token" value="' . $token .'">',$this->html_code);
    }
    echo $this->html_code;
}

/*
|--------------------------------------------------------------------------
| @Function: addBody
|--------------------------------------------------------------------------
*/

private function addBody($value){
    $html = str_replace(array("\n", "\s", "\t"), array('', '', ''), $value);
    preg_match("/<body(.*?)>(.*?)<\/body>/", $html, $out);
    //Se não encontrar a tag body, considera tudo como body
    if(count($out) == 0){
        array_push($this->page_body, $value);
    }
    else{
        array_push($this->page_body, $out[2]);
    }
    
}

/*
|--------------------------------------------------------------------------
| @Function: headProcessing
|--------------------------------------------------------------------------
| This function mount the 'head' tag
*/

private function headProcessing($code){
    //Retira espaços, quebra de linha e tabulação
    $code = str_replace(array("\n", "\s", "\t"), array('', '', ''), $code);
    //Get HEAD tag
    preg_match_all("/<head>(.*?)<\/head>/", $code, $out);
    //Se não for encontrado nada, não adiciona nada
    if(count($out[0]) == 0){
        return $code;
    }
    $tag_head = $out[1][0];
    //Remove head tag from code
    $code = str_replace($out[0][0], "", $code);
    //Obtém os parâmetros 'href' da tags 'link'
    preg_match_all('/<link( rel=".*?")?( type=".*?")? href="(.*?)">/', $tag_head, $out);
    foreach($out[3] as $value){
        array_push($this->tag_link, $value);
    }
    //Obtém os parâmetros 'src' da tags 'script'
    preg_match_all('/<script( type=".*?")? src="(.*?)">(.*?)<\/script>/', $tag_head, $out);
    foreach($out[2] as $value){
        array_push($this->tag_script, $value);
    }
    //Get JavaScript code
    preg_match_all('/<script( type=".*?")? >(.*?)<\/script>/', $tag_head, $out);
    if(isset($out[2]))
    foreach($out[2] as $value){
        array_push($this->tag_javaScript, $value);
    }
    //Get Style Code
    preg_match_all('/<style( type=".*?")? >(.*?)<\/style>/', $tag_head, $out);
    if(isset($out[2]))
    foreach($out[2] as $value){
        array_push($this->tag_style, $value);
    }
    //Get meta tag
    preg_match_all('/<meta(.*?)>/', $tag_head, $out);
    if(isset($out[0]))
    foreach($out[0] as $value){
        array_push($this->tag_meta, $value);
    }

    return $code;
}


/*
|--------------------------------------------------------------------------
| @Function: loadVars
|--------------------------------------------------------------------------
| This function load vars in the template or view
*/

private function loadVars($variables = array(), $code){
    foreach($variables as $variable => $value){
        if($this->countVar($variable, $code) > 0)
        $code = preg_replace("/\{\{ ".$variable." \}\}/", $value, $code);
    }
    return $code;
}

/*
|--------------------------------------------------------------------------
| @Function: searchControllerReference
|--------------------------------------------------------------------------
|
*/

private function searchControllerReference($code){
    preg_match_all('/{{ (.*?)@(.*?) }}/', $code, $out);

    $i = 0;
    $controllerVars = array();
    foreach($out[1] as $controller){
        array_push($controllerVars, array('controller'=>$controller, 'function'=>$out[2][$i]));
        $i++;
    }

    foreach($controllerVars as $ct){
        include_once ADS_CONTROLLERS . $ct['controller'] . '.php';
        $resp = call_user_func($ct['function']);
        $code = preg_replace('/{{ ' . $ct['controller'] . '@' . $ct['function'] . ' }}/', $resp, $code);
    }

    return $code;
}

<<<<<<< HEAD
=======
/*
|--------------------------------------------------------------------------
| @Function: searchTemplateReference
|--------------------------------------------------------------------------
|
*/

>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
private function searchTemplateReference($code){
    preg_match_all('/{{ --(.*?)-- }}/', $code, $out);

    $templates = array();

    foreach ($out[1] as $temp) {
        array_push($templates, $temp);
    }

    foreach($templates as $temp){
        $templateCode = File::getContent($temp, 'TEMPLATE');
        if(!$templateCode)
            throw new \Exception('Templete@loadTemplates: ' . $temp . ' not found!');

        $code = preg_replace('/{{ --' . $temp . '-- }}/', $templateCode, $code);
    }

    return $code;
}

/*
|--------------------------------------------------------------------------
<<<<<<< HEAD
=======
| @Function: searchForeachReference
|--------------------------------------------------------------------------
|
*/

private function searchForeachReference($vars, $code){
    
    $verify = new ForeachDecoder($vars, $code);
    return $verify->verify_command();

}

private function searchForeachJSReference($vars, $code) {

    $verify = new ForeachJS($vars, $code);
    return $verify->verify_command();

}

/*
|--------------------------------------------------------------------------
| @Function: searchConditionalReference
|--------------------------------------------------------------------------
|
*/

private function searchConditionalReference($vars, $code){

    $verify = new ConditionalDecoder($vars, $code);
    return $verify->verify_command();

}

/*
|--------------------------------------------------------------------------
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
| @Function: countVar
|--------------------------------------------------------------------------
| Check how many times the same variable appears
*/

private function countVar($variable, $code){
    preg_match_all("/\{\{ ".$variable." \}\}/", $code, $match);
    if(isset($match[0][1]))
        return 2;   //mais de uma correspondencia
    if(isset($match[0][0]))
        return 1;   //uma correspondencia
    else
        return 0;   //nenhuma correspondência
}

/*
|--------------------------------------------------------------------------
| @Function: rmVar
|--------------------------------------------------------------------------
| Remove one variable
*/

private function rmVar($variable, $code){
    //Remove todas as variáveis modelo (TV)
    if($variable == null)
        return preg_replace('/\{\{ (.*?) \}\}/', null, $code);
    else
        return loadVars(array($variable =>null), $code);
}


}//endClass
