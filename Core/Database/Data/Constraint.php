<?php

namespace Core\Database\Data;

class Constraint
{
    private bool $isNotNull;
    private bool $isUnique;
    private bool $isAutoIncrement;
    private bool $isPrimaryKey;
    private bool $isForeignKey;
    private bool $hasDefault;
    private bool $hasCheck;
    private $defaultValue;
    private $check;
    private string $foreignKeyTable;

    function __construct(){
        $this->isNotNull = false;
        $this->isUnique = false;
        $this->isPrimaryKey = false;
        $this->isForeignKey = false;
        $this->hasDefault = false;
        $this->hasCheck = false;
        $this->isAutoIncrement = false;

    }

    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/
    
    public function isNotNull(){
        return $this->isNotNull;
    }

    public function isUnique(){
        return $this->isUnique;
    }

    public function isAutoIncrement(){
        return $this->isAutoIncrement;
    }

    public function isPrimaryKey(){
        return $this->isPrimaryKey;
    }

    public function isForeignKey(){
        return $this->isForeignKey;
    }

    public function hasDefault(){
        return $this->hasDefault;
    }

    public function hasCheck(){
        return $this->hasCheck;
    }

    public function getDefaultValue(){
        return $this->defaultValue;
    }

    public function getCheck(){
        return $this->check;
    }

    /*-------------------------------------------------------------------------------- 
	|	SETTERS
    ----------------------------------------------------------------------------------*/

    public function setIsNotNull($value){
        $this->isNotNull = $value;
    }

    public function setIsUnique($value){
        $this->isUnique = $value;
    }

    public function setIsAutoIncrement($value){
        $this->isAutoIncrement = $value;
    }

    public function setIsPrimaryKey($value){
        $this->isPrimaryKey = $value;
    }

    public function setIsForeignKey($value){
        $this->isForeignKey = $value;
    }

    public function setHasDefault($value){
        $this->hasDefault = $value;
    }

    public function setHasCheck($value){
        $this->hasCheck = $value;
    } 

    public function setDefaultValue($value){
        $this->defaultValue = $value;
    }
    
    public function setForeignKeyTable($value){
        $this->foreignKeyTable = $value;
    }
}