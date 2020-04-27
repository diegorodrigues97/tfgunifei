<?php

namespace Core;

class ConditionalDecoder
{
    private $html_code; // view html code received 
    private $vars_list; // vars passed by the controller
    private $html_return; // view html code returned
    
    function __construct($vars, $code)
    {
        $this->vars_list = $vars;
        $this->html_code = $code;
    }
    
    function verify_command()
    {
        // disambiguation
        $html_code = $this->html_code;
        // pattern to find (or not) the occurrences of if-elseif-else
        $pattern = '/(@if\s*\(((?:(?:(?:"(?:(?:\\\\")|[^"])*")|(?:\'(?:(?:\\\\\')|[^\'])*\'))|[^\(\)]|\((?1)\))*+)\)\s*{((?:(?:(?:"(?:(?:\\\\")|[^"])*")|(?:\'(?:(?:\\\\\')|[^\'])*\'))|[^{}]|{(?2)})*+)})\s*(?:(?:(@else\s*{((?:(?:(?:"(?:(?:\\\\")|[^"])*")|(?:\'(?:(?:\\\\\')|[^\'])*\'))|[^{}]|{(?3)})*+)})\s*)|(?:(@elseif\s*\(((?:(?:(?:"(?:(?:\\\\")|[^"])*")|(?:\'(?:(?:\\\\\')|[^\'])*\'))|[^\(\)]|\((?4)\))*+)\)\s*{((?:(?:(?:"(?:(?:\\\\")|[^"])*")|(?:\'(?:(?:\\\\\')|[^\'])*\'))|[^{}]|{(?5)})*+)})\s*))*/m';
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
            /*
                --- HOW THIS PART OF THE CODE WORK ---
                (a): the block if will be processed and if it's TRUE, then the block will be replaced by the content,
                but if it's FALSE, the block will be replaced by an empty content ('').

                (b): get the type of conditional structure has in the html code (4: if, 6: if-else, 9: if-elseif-else).

                (c): if type is different of 4:
                    (c.1) - type 6: ->if the "if block" is TRUE, then the "else block" will be replaced by an empty content
                                    ->if the "if block" is FALSE, then the "else content" will be passed to the hmtl code
                    (c.2) - type 9: ->if the "if block" is TRUE, then the "elseif" and the "else block" will be replaced by an empty content
                                    ->if the "if block" is FALSE, then the "elseif block" will be processed
                            (c.2.1): ->if the "elseif block" is TRUE, then the "elseif content" will be passed to the hmtl code 
                                       and the "else block" will be replaced by an empty content
                                      ->if the "elseif block" is FALSE, then the "else content" will be processed
            */

            // get the quantity of if blocks
            $count_matches = count($matches);
            for($match = 0; $match < $count_matches; $match++)
            {
                // if data
                $if_block = $matches[$match][1];
                $if_condition = $matches[$match][2];
                $if_content = $matches[$match][3];
                // 0 - FALSE or 1 - TRUE
                $response = $this->process_condition($if_condition);
                
                if($response)
                {
                    // replace the if block by the if content
                    $html_code = str_replace($if_block, $if_content, $html_code);
                }
                else
                {
                    // replace the if block by '' (empty content)
                    $html_code = str_replace($if_block, '', $html_code);
                }
                 
                $count = count($matches[$match]);
                
                switch ($count) 
                {
                    case 4: // structure: if
                        // code...
                    break;
                    
                    case 6: // structure: if - else
                        $else_block = $matches[$match][4];
                        $else_content = $matches[$match][5];
                        
                        // if "if block == TRUE": remove
                        if($response)
                        {
                            // replace the else block by '' (empty content)
                            $html_code = str_replace($else_block, '', $html_code);
                        }
                        else
                        {
                            // replace the else block by the else content
                            $html_code = str_replace($else_block, $else_content, $html_code);
                        }
                    break;
                    
                    case 9: // structure: if - elseif - else
                        $else_block = $matches[$match][4];
                        $else_content = $matches[$match][5];
                        $elseif_block = $matches[$match][6];
                        $elseif_condition = $matches[$match][7];
                        $elseif_content = $matches[$match][8];
                        
                        if($response)
                        {
                            // replace the elseif block by '' (empty content)
                            $html_code = str_replace($elseif_block, '', $html_code);
                            // replace the else block by '' (empty content)
                            $html_code = str_replace($else_block, '', $html_code);
                        }
                        else
                        {
                            $response = $this->process_condition($elseif_condition);
                            
                            if($response)
                            {
                                // replace the elseif block by the elseif content
                                $html_code = str_replace($elseif_block, $elseif_content, $html_code);
                                 // replace the else block by '' (empty content)
                                $html_code = str_replace($else_block, '', $html_code);
                            }
                            else
                            {
                                // replace the elseif block by '' (empty content)
                                $html_code = str_replace($elseif_block, '', $html_code);
                                 // replace the else block by the else content
                                $html_code = str_replace($else_block, $else_content, $html_code);
                            }
                        }
                    break;    
                } 
            }
            $this->html_return = $html_code;
            return $this->html_return;
        }
    }
    
    private function process_condition($condition)
    {
        // disambiguation
        $vars_list = $this->vars_list;
        // pattern to find variables in the condition
        $pattern = '#\$(\w+)#';
        preg_match_all($pattern, $condition, $matches);
        // optimization to the for block
        // $var_name
        $vars_full = $matches[0];
        // var_name
        $vars_short = $matches[1];
        // block to replace var name by the var value
        foreach ($vars_short as $key_one => $value_one) 
        {
            foreach ($vars_list as $key_two => $value_two) 
            {
                if($value_one == $key_two)
                {
                    $condition = str_replace($vars_full[$key_one], $value_two, $condition);
                }  
            }
        }
        // test with the values to test
        $test = "return $condition ;";
        // block to get a possible error
        try 
        {   
            // evaluate the test
            $process = eval($test);
            // 0 - FALSE or 1 - TRUE
            return $process;
        }
        catch (ParseError $e) 
        {
            // it means that an error occured, then an exception will be thrown
            throw new Exception($e->getMessage());
        }
    }
}

?>