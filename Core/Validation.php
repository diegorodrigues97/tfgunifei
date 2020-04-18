<?php

namespace Core;

/*
|--------------------------------------------------------------------------
| @Class: Validation
|--------------------------------------------------------------------------
|
| @ClassFunction: This class valid input
|
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|
*/

class Validation{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
| &id
| &id_exercise
*/

private $filter;
private $input;

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
|
*/

function __construct($input = array(), $filter = array()){
    $this->input = $input;
    $this->filter = $filter;
}

/*
|--------------------------------------------------------------------------
| @StaticFunctionName: isValid
|--------------------------------------------------------------------------
|
*/

static function isValid($input = array(), $filter = array(), $required = null, $fill = false, $fillWith = null){
    if(empty($input))
        return array("error"=>true, "message"=>"Validation@isValid: No validation parameters passed!", "miss"=>true);
    if(empty($filter))
        return array("error"=>true, "message"=>"Validation@isValid: No filter specified!", "miss"=>true);

    //Prepare the filter, if key is not specified: [valueArray] => [keyArray]
    foreach($filter as $filterKey => $filterValue){
        if(gettype($filterKey) == 'integer')
            $filter2[$filterValue] = null;
        else
            $filter2[$filterKey] = $filter[$filterKey];
    }
    $filter = $filter2;

    //Creates a response variable
    $output = array();
    $output["error"] = false;
    $output["miss"] = false;
    $output["message"] = "";
    //compare 'input' with the 'filter'
    foreach($input as $key => $value){
        //Check the name of input variable with the filter specified
        if(!array_key_exists($key, $filter)){
            $output["error"] = true;
            $output["message"] = "Validation@isValid: Parameter can not be passed: ".$key;
            return $output;
        }
        //Check whether data type is correct
        if($filter[$key] != null){
            if(gettype($value) !=  $filter[$key]){
                $output["error"] = true;
                $output["message"] = "O tipo do parâmetro está incorreto: ".$key;
                return $output;
            }
        }
    }
    //Verifica se falta campos daqueles previstos no filtro
    $absent = "";
    foreach($filter as $key =>$value){
        if(!array_key_exists($key, $input)){
            $output["miss"] = true;
            $absent = $absent."[".$key."]";
            $output["absent"] = $absent;
        }

    }
    //Verifica se o terceiro parâmetro foi especificado
    if(!is_null($required)){
        $output['requiredFields'] = true;
        //Retira todos os espacos
        $required = str_replace(array("\n", "\s", "\t"), array('', '', ''), $required);
        //Separa os dados
        $attributes = explode(",",$required);
        foreach($attributes as $key){
            if(!array_key_exists($key, $input))
                $output['requiredFields'] = false;
        }
    }
    //Check fourth function parameter
    if($fill){
        $output['fill'] = array();
        foreach ($filter as $filterKey => $filterValue) {
            if(!array_key_exists($filterKey, $input))
                $output['fill'][$filterKey] = $fillWith;
            else
                $output['fill'][$filterKey] = $input[$filterKey];
        }
    }


    return $output;
}

/*
|--------------------------------------------------------------------------
| @Function: isDate
|--------------------------------------------------------------------------
|
*/
function isDate($value, $filter){
    if(DateTime::createFromFormat($filter, $value))
        return true;
    else
        return false;
}


/*
|--------------------------------------------------------------------------
| @Function: str_textConvert
|--------------------------------------------------------------------------
|
*/

public function str_textConvert($str, $options = array()){
    //Se a entrada for array, chama a função novamente
    if(is_array($str)){
        foreach($str as $key => $value){
            if(is_array($value)){
                $str[$key] = $this->str_textConvert($value);
            }else

            {
            //Verifica se o campo pode ser tratado
            if(!in_array($key, $options)){
                //Remove tags
                $str[$key] = filter_var($str[$key], FILTER_SANITIZE_STRING);
                //Remove *
                $str[$key] = preg_replace('/\*/','',$str[$key]);
                //Remove ;
                $str[$key] = preg_replace('/\;/','',$str[$key]);
                //Remove algumas palavras chaves PHP
                $keywords = array( '/die\(\)/i', '/eval\(\)/i', '/exit\(\)/i','/function/i','/require\(\)/i', '/require_once\(\)/i', '/unset\(\)/i', '/copy\(\)/i');
                $str[$key] = preg_replace($keywords, '', $str[$key]);
                //Remove todas as constantes PHP
                $predefined_constants = array('/__CLASS__/', '/__DIR__/', '/__FILE__/', '/__FUNCTION__/', '/__LINE__/', '/__METHOD__/', '/__NAMESPACE__/', '/__TRAIT__/');
                $str[$key] = preg_replace($predefined_constants, '', $str[$key]);
                //Retira excesso  de espaços em branco
                $str[$key] = preg_replace('/\s\s+/', ' ', $str[$key]);
                }
            }
        }

        return $str;
    }
}

/*
|--------------------------------------------------------------------------
| @Function: processResponse
|--------------------------------------------------------------------------
|
*/



}
