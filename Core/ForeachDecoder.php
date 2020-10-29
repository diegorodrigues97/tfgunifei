<?php

namespace Core;

class ForeachDecoder
{
    private $html_code; // view html code received 
    private $vars_list; // vars passed by the controller
    private $html_return; // view html code returned
    private $element_name;
    private $element_property;
    
    function __construct($vars, $code)
    {
        $this->vars_list = $vars;
        $this->html_code = $code;
    }

    function verify_command()
    {
        // disambiguation
        $html_code = $this->html_code;

        // pattern to find (or not) the occurrences of foreach
        $pattern = '#@foreach\s?\(\s*(\w+)\s*(\w+)\s*(in)\s*(\w+)\s*\)\{([\s\S]+)\}#';

        // match all to capture more than one occurrence
        preg_match_all($pattern, $html_code, $matches, PREG_SET_ORDER, 0);

        // it means that there isn't any occurrence
        if(empty($matches))
        {
            $this->html_return = $html_code;
            return $this->html_return;
        }

        // it means that there is one or more occurrence
        else
        {
            $foreach = $matches[0][0];
            $this->element_name = $matches[0][2];
            $list_name = $matches[0][4];
            $foreach_content = $matches[0][5];

            foreach ($this->vars_list as $key => $value) 
            {
                if($key == $list_name) {
                    $exist = TRUE;
                }
            }

            if(!$exist)
            {
                throw new \Exception("Failed", 5);
            }

            $html_return = '';

            $ttype_vars_list = gettype($this->vars_list[$list_name]);

            if($ttype_vars_list == "array") 
            {
                $element_pattern = "#{$this->element_name}\[\"(\w+)\"\]#";
                
                preg_match_all($element_pattern, $foreach_content, $matches_elem);

                if(!empty($matches_elem[0])) 
                {
                    $access_array = 0; // element["property"]
                }
                else 
                {
                    $element_pattern = "#{$this->element_name}#";
                    preg_match_all($element_pattern, $foreach_content, $matches_elem);
                    if(empty($matches_elem[0])) 
                    {
                        throw new \Exception("Failed", 5);
                    }
                    $access_array = 1; // element[0], element[1], ...
                }

                if($access_array == 0)
                {
                   $property = key($this->vars_list[$list_name][0]); // property from vars

                   $this->element_property = $matches_elem[1][0]; // property from code

                    if($property != $this->element_property)
                    {
                        throw new \Exception("Failed: Property", 5);
                    }

                    foreach ($this->vars_list[$list_name] as $key => $value) 
                    {
                        $aux = $foreach_content;
                        $replace = "{{ ".$this->element_name."[\"".$this->element_property."\"] }}";
                        $aux = str_replace($replace, $value[$this->element_property], $aux);
                        $html_return = $html_return.$aux;
                        
                    }

                    $html_code = str_replace($foreach, $html_return, $html_code);

                    $this->html_return = $html_code;
                    return $this->html_return;

                }

                elseif($access_array == 1)
                {
                    foreach ($this->vars_list[$list_name] as $key => $value) 
                    {
                        $aux = $foreach_content;
                        $replace = "{{ ".$this->element_name." }}";
                        $aux = str_replace($replace, $value, $aux);
                        $html_return = $html_return.$aux;
                        
                    }

                    $html_code = str_replace($foreach, $html_return, $html_code);

                    $this->html_return = $html_code;
                    return $this->html_return;
                }

                else 
                {
                    throw new \Exception("Failed: Access Array", 5);
                }

            }

            elseif($ttype_vars_list == "object") 
            {
                $aux = (array)$this->vars_list[$list_name];
                $property = key($aux[0]); // property from vars

                $element_pattern = "#{$this->element_name}\.(\w+)#";
                preg_match_all($element_pattern, $foreach_content, $matches_elem);

                if(empty($matches_elem[0])) 
                {
                    throw new \Exception("Failed", 5);
                }
                
                $this->element_property = $matches_elem[1][0]; // property from code

                if($property != $this->element_property)
                {
                    throw new \Exception("Failed: Property", 5);
                }

                foreach ($this->vars_list[$list_name] as $key => $value) 
                {
                    $aux = $foreach_content;
                    $replace = "{{ ".$this->element_name.".".$this->element_property." }}";
                    $aux = str_replace($replace, $value[$this->element_property], $aux);
                    $html_return = $html_return.$aux;
                    
                }

                $html_code = str_replace($foreach, $html_return, $html_code);

                $this->html_return = $html_code;
                return $this->html_return;
            }

            else 
            {
                throw new \Exception("Failed", 5);
            }
        }
    }
}