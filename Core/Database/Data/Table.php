<?php

namespace Core\Database\Data;

class Table 
{
    private string $name;
    private $columns = [];
    private $namespace;

    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/    
    
    public function getName(){
        return $this->name;
    }
    
    public function getColumn($name){
        foreach($this->columns as $column){
            if($column->getName() == $name)
                return $column;
        }
        
        throw new \Exception('Column not found: ' . $name);
    }

    public function getColumns(){
        return $this->columns;
    }

    public function getNamespace(){
        return $this->namespace;
    }

    public function getPrimaryKey(){
        foreach($this->columns as $column){
            if($column->getConstraint()->isPrimaryKey())
                return $column;
        }
    }

    /*-------------------------------------------------------------------------------- 
	|	SETTERS
    ----------------------------------------------------------------------------------*/    

    public function setName($value){
        $this->name = $value;
    }
    
    public function addColumn(Column $column){
        $this->columns[] = $column;
    }

    public function setNamespace(string $value){
        $this->namespace = $value;
    }
    
}