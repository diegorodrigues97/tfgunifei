<?php

namespace Core;

use Core\Exceptions\ArrayListException;

class ArrayList  implements Iterator
{

    /*-------------------------------------------------------------------------------- 
	|	PROPERTIES
	----------------------------------------------------------------------------------*/
    private Array $items; /*storage all items in list*/
    private int $position; /* current position in list*/
    private Array $result; 
    private string $currentPropertie;
    private string $closure; /* storage a closure function */
    

    public function __construct(){
        $this->position = 0;
    }

    /*-------------------------------------------------------------------------------- 
    |	INTERFACE IMPLEMENTATION
    | This functions implement Iterator Interface
	----------------------------------------------------------------------------------*/

    public function rewind(){
        $this->position = 0;
    }

    public function current(){
        return $this->items[$this->Position];
    }

    public function key(){
        return $this->position;
    }

    public function next(){
        ++$this->position;
    }

    public function valid(){
        return isset($this->items[$this->position]);
    }

    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/
    
    

    /*-------------------------------------------------------------------------------- 
	|	HELPERS
	----------------------------------------------------------------------------------*/

    private function getClosure(){
        return 'return ' . $this->closure . ";";
    }

    function addStatementInClosure(string $value){
        $this->closure = $this->closure . " $value ";
    }
    
    
    /*-------------------------------------------------------------------------------- 
	|	STATEMENTS
	----------------------------------------------------------------------------------*/
    
    public function where($value)
    {
        $this->addStatementInClosure('$obj->' . $value); 
        return $this;
    }

    public function and($value)
    {
        $this->addStatementInClosure('&& $obj->' . $value); 
        return $this;
    }

    public function or($value)
    {
        $this->addStatementInClosure('|| $obj->' . $value); 
        return $this;
    }

    public function equal($value)
    {
        $this->addStatementInClosure('== ' . "'" . $value . "'"); 
        return $this;
    }

    public function biggerTo($value)
    {
        $this->addStatementInClosure('> ' . $value); 
        return $this;
    }

    public function biggerAndEqualTo($value)
    {
        $this->addStatementInClosure('>= ' . $value); 
        return $this;
    }

    public function lessTo($value)
    {
        $this->addStatementInClosure('< ' . $value); 
        return $this;
    }

    public function lessAndEqualTo($value)
    {
        $this->addStatementInClosure('<= ' . $value); 
        return $this;
    }

    /*-------------------------------------------------------------------------------- 
    |	PUBLIC ACTIONS
    | Actions that User can use
	----------------------------------------------------------------------------------*/
    
    public function list()
    {
        return array_filter($this->items, function($obj) {return eval($this->getClosure());});
    }

  

}