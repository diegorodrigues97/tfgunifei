<?php

class CheckCommands
{
    private $HtmlCode;
    
    function __construct($Code)
    {
        $HtlmCode = $Code;
    }

    function SearchCommand()
    {
        return $this->VerifyForeach();

    }

    function VerifyForeach()
    {
        $basetext = file_get_contents('hello.s');

        $pattern = '#foreach(\s?)\((\s*)(\w+)(\s*)(\w+)\s*(in)\s*(\w+)(\s*)\)\{([\s\S]+)\}#';

        preg_match($pattern, $basetext, $matches);

        foreach ($matches as $key => $value) 
        {
            if($value == "in")
            {
                $varElement = $matches[$key - 1]; // garanto aqui que peguei o elemento da lista
                $varList = $matches[$key + 1]; // garanto aqui que peguei a lista
            }
        }

        $textRef = end($matches); // o texto entre chaves fica sempre na ultima posição do array, pego ele aqui para a extração das propriedades do elemento

        $pattern_elem = "#{$varElement}\.(\w+)#";

        preg_match_all($pattern_elem, $textRef, $matches_elem);


        foreach ($matches_elem[1] as $key => $value)  // garanto que estou acessando as referências dos obj
        { 
            $varNaoSeiONome[$key] = $value;
        }

        print_r($varNaoSeiONome);

        

    }



}

$check = new CheckCommands(" ");
$check->VerifyForeach();



