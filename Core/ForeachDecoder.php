<?php

namespace Core;

class ForeachDecoder
{
    private $html_code; // conteudo do arquivo .html
    private $list_content_structure; // conteudo da lista
    private $pattern_results; // indice e valor ou atributo e valor
    private $element_name; // elemento da lista
    private $list_name; // lista
    private $element_type; // tipo do elemento da lista
    private $list_content_values; // valores da lista
    private $html_return; // html tratado
    private $foreach_content; // conteudo dentro das chaves do foreach
    private $element_index; // atributo objeto ou indice array
    
    function __construct($vars, $code)
    {
        $this->list_content_structure = $vars;
        $this->html_code = $code;
    }

    function verify_command()
    {
        $html_code = $this->html_code;

        $comment_pattern = '#\<\!\-\-([\s\S]+)\-\-\>#';
        $verify = preg_match($comment_pattern, $html_code, $matches);
        $replace = '';
        $matches = implode ( ' ' , $matches );
        $html_code = str_replace($replace, $matches, $html_code);

        $foreach_pattern = '#@foreach(\s?)\((\s*)(\w+)(\s*)(\w+)\s*(in)\s*(\w+)(\s*)\)\{([\s\S]+)\}#';
        preg_match($foreach_pattern, $html_code, $matches);

        if(empty($matches))
        {
            echo $this->html_code;
        }

        else 
        {
            foreach ($matches as $key => $value) 
            {
                if($value == "in")
                {
                    $this->element_name = $matches[$key - 1]; // pego o elemento
                    $this->list_name = $matches[$key + 1]; // pega a lista
                }
            }

            $aux_list_content_structure = $this->list_content_structure;
            $exists = False;

            foreach ($aux_list_content_structure as $key => $value)
            {
                if($key == $this->list_name)
                {
                    $exists = True;
                }
            }

            if(!$exists)
            {
                throw new \Exception("Failed", 5);
            }
            // busca pelo element_name seguioa de um 'ponto' e de um texto com letras minusculas
            // ou seja, busca por um array de objetos
            $foreach_element_pattern = "#{$this->element_name}\.(\w+)#"; 
            // o texto entre chaves fica sempre na ultima posição do array, pego ele aqui para a extração das propriedades do elemento
            $this->foreach_content = end($matches); 
            preg_match_all($foreach_element_pattern, $this->foreach_content, $matches_elem);
            
            if(!empty($matches_elem[1]))
            {
                foreach ($matches_elem[1] as $key => $value)  // garanto que estou acessando as referências dos obj
                { 
                    $this->pattern_results[$key] = $value;
                }
                
                $this->element_index = $this->pattern_results[0]; // atributo do objeto
                $this->element_type = "object";
            }
            
            else
            {
                // busca pela element_name seguida de um colchetes seguido de aspas
                // seguido de um texto com letras minusculas seguido de aspas seguido de outro colchetes
                // ou seja,busca por um array de array
                $foreach_element_pattern = "#{$this->element_name}(\[)(\")(\w+)(\")(\])#";
                preg_match_all($foreach_element_pattern, $this->foreach_content, $matches_elem);

                foreach ($matches_elem[3] as $key => $value)  // garanto que estou acessando as referências do array
                { 
                    $this->pattern_results[$key] = $value;
                }

                $this->element_index = $this->pattern_results[0]; // pega o indice do array
                $this->element_type = "array";
            }

            $this->list_content_values = $this->list_content_structure[$this->list_name];

            if(gettype($this->list_content_values) != $this->element_type)
            {
                throw new \Exception("Invalid foreach type");
            }

            if($this->element_type == "array")
            {
                foreach ($this->list_content_values as $key => $value) {
                    
                    if(gettype($value) == "array")
                    {
                        $aux = $this->foreach_content;
                        $replace = "{{ ".$this->element_name."[\"".$this->element_index."\"] }}";
                        $aux = str_replace($replace, $value[$this->element_index], $aux);
                        $this->html_return = $this->html_return.$aux;
                    }

                    else 
                    {
                        $aux = $this->foreach_content;
                        $aux = str_replace("{{ ".$this->element_name." }}", $value, $aux);
                        $this->html_return = $this->html_return.$aux;
                    }  
                }
                echo $this->html_return;
            }

            elseif ($this->element_type == "object") 
            {
                foreach ($this->list_content_values as $key => $value) 
                {
                    if(gettype($value) == "array")
                    {
                        $aux = $this->foreach_content;
                        $replace = "{{ ".$this->element_name.".".$this->element_index." }}";
                        $aux = str_replace($replace, $value[$this->element_index], $aux);
                        $this->html_return = $this->html_return.$aux;
                    } 
                }
                echo $this->html_return;
            } 
     
            else 
            {
                throw new \Exception("Variable type not expected");
            }
        }
    }
}





