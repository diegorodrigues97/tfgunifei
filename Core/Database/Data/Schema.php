<?php

namespace Core\Database\Data;


use Core\Database\Data\Table;

class Schema
{
    private string $name;
    private array $tables;

    public function __construct()
    {
        $this->tables =  [];
    }

    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/

    public function getTable($name){
        foreach($this->tables as $table){
            if($table->getName() == $name)
                return $table;
        }
        
        throw new Exception('Table not found: ' . $name);
    }

    public function getTables(){
        return $this->tables;
    }

    /*-------------------------------------------------------------------------------- 
	|	SETTERS
    ----------------------------------------------------------------------------------*/

    public function setName(string $name){
        $this->name = $name;
    }
    
    public function addTable(Table $table){
        $this->tables[] = $table;
    }
    
    
}