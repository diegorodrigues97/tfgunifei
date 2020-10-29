<?php

namespace Core\Database\Data;

class Column{
    private string $name;
    private string $nickName; //will be reflect to database
    private string $type;
    private string $typeLength;
    private bool $isUserDefined;
    private Constraint $constraint;

    function __construct(){
        $this->constraint = new Constraint();
        $this->typeLength = '0';
    }

    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/    
    
    public function getType(){
        return $this->type;
    }
    
    public function getName(){
        return $this->name;
    }

    public function getConstraint() : Constraint {
        return $this->constraint;
    }

    public function isUserDefined(){
        return $this->isUserDefined;
    }

    public function getTypeLength(){
        return $this->typeLength;
    }
    

    /*-------------------------------------------------------------------------------- 
	|	SETTERS
    ----------------------------------------------------------------------------------*/          

    public function setType($value){
        $this->type = $value;
    }

    public function setName($value){
        $this->name = $value;
    }
    
    public function setNickName($value){
        $this->nickName = $value;
    }

    public function setIsUserDefined(bool $value){
        $this->isUserDefined = $value;
    }

    public function setTypeLength(string $value){
        $this->typeLength = $value;
    }
}