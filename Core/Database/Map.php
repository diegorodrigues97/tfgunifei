<?php

namespace Core\Database;


use Core\Database\Data\Schema;
use Core\Database\Data\Table;
use Core\Database\Data\Column;
use Core\Database\Data\Statement;
use Core\AppException;
use Core\Exceptions\DatabaseMapException;

class Map{
    
    /*Properties*/
    private Schema $schema;
    private $currentEntity;
    private $entityReflection;
    private array $parentClass;

    public static $CONSTRAINTS = [  
                                    0 => 'PK', 
                                    1 => 'AUTO INCREMENT', 
                                    2 => 'NOT NULL',
                                    3 => 'FK',
                                    4 => 'DEFAULT',
                                    5 => 'CHECK',
                                    6 => 'UNIQUE'
                                ];
    public static $TYPES_ALLOWED = ['INT', 'DOUBLE', 'DECIMAL', 'VARCHAR', 'TEXT', 'DATETIME', 'BIT', 'CHAR', 'DATETIME'];
    
    function __construct(){
        $this->schema = new Schema();
    }

    
    /*-------------------------------------------------------------------------------- 
	|	GETTERS
    ----------------------------------------------------------------------------------*/
    public function getSchema(){
        return $this->schema;
    }
    
    
    /*-------------------------------------------------------------------------------- 
	|	ACTIONS
    ----------------------------------------------------------------------------------*/
    public function addEntity(string $className){
        $this->entityReflection = new \ReflectionClass($className);
        $this->currentEntity = $className;
                
        $table = new Table();
        $table->setName($className);
        
        if($this->entityReflection->inNamespace()){
            $table->setNamespace($this->entityReflection->getNamespaceName());
        }
        
        foreach($this->entityReflection->getProperties() as $propertie){
            $column = new Column();
            $column->setName($propertie->getName());
            $column->setType($propertie->getType()->getName());
            $column->setIsUserDefined($this->PropertieisUserDefined($propertie->getType()->getName()));
            
            $table->addColumn($column);
        }
        
        $this->schema->addTable($table);
        
        return $this;
    }
    
    public function constraint(string $propertie, $constraints = []){
        //get column
        $column = $this->schema->getTable($this->currentEntity)->getColumn($this->getPropertieNameDefinedByUser($propertie));
        $column->setType($this->getPropertieTypeDefinedByUser($propertie)['name']);
        $column->setTypeLength($this->getPropertieTypeDefinedByUser($propertie)['length']);

        //extract constraints passed
        foreach($constraints as $key => $value){
            $this->addConstraint($column, $key, $value);
        }

        return $this;
    }

    public function addReference(string $propertieName, string $targetTabe){
        $column = $this->schema->getTable($this->currentEntity)->getColumn($propertieName);    
        $column->getConstraint()->setForeignKeyTable($propertieName);

        return $this;
    }
    
    
    /*-------------------------------------------------------------------------------- 
	|	HELPETS
    ----------------------------------------------------------------------------------*/

    private function PropertieisUserDefined(string $typeName){
        $defaults = ["bool","int", "scalar", "real","double","string","array","object","resource","NULL","unknown type"];

        foreach($defaults as $default){
            //echo $typeName . PHP_EOL;
            if($typeName == $default)
                return false;
        }

        return true;
    }
    
    private function addConstraint(Column $column, $key, $value){
        $constraint = null;
        $constraint_value = null;

        if(getType($key) == 'string'){
            $constraint = $key;
            $constraint_value = $value;
        }
        else{
            $constraint = $value;
        }
            
        //Check if value passed by User is defined
        $constraint = $this->constraintExists($constraint);
        //Set constraints
        $constraintDefinitions = $column->getConstraint();

        switch($constraint){
            case Map::$CONSTRAINTS[0]: //PK
                $constraintDefinitions->setIsPrimaryKey(true);
            break;
            case Map::$CONSTRAINTS[1]: //AUTO INCREMENT
                $constraintDefinitions->setIsAutoIncrement(true);
            break;
            case Map::$CONSTRAINTS[2]: //NOT NULL
                $constraintDefinitions->setIsNotNull(true);
            break;
            case Map::$CONSTRAINTS[3]: //FK
                $constraintDefinitions->setIsForeignKey(true);
                $constraintDefinitions->setForeignKeyTable($constraint_value);
            break;
            case Map::$CONSTRAINTS[4]: //DEFAULT
                $constraintDefinitions->setHasDefault(true);
                $constraintDefinitions->setDefaultValue($constraint_value);
            break;
            case Map::$CONSTRAINTS[5]: //CHECK
                $constraintDefinitions->hasCheck(true);
                $constraintDefinitions->setCheck($constraint_value);
            break;
            case Map::$CONSTRAINTS[6]: //UNIQUE
                $constraintDefinitions->setIsUnique(true);
            break;                
            default:
                throw new DatabaseMapException("Constraint $constraint not implemented!");
            break;
        }
    }

    private function constraintExists(string $input){
        foreach(Map::$CONSTRAINTS as $defined){
            //echo $input . ' ' . $defined . PHP_EOL;
            if(stripos($defined, $input) !== false)
                return $defined;
        }

        throw new DatabaseMapException("Constraint not exists: $input");
    }

    private function getPropertiesInEntity(){
        foreach($this->entityReflection->getParentClass() as $parentClass){
            $parentClasses[] = $parentClass;
        }
        return $parentClasses;
    }
    
    private function getPropertieNameDefinedByUser(string $input){
        if(!preg_match_all('/^(.*?):(.*?)$/', $input, $matches))
            throw new DatabaseMapException('Constraint propertie definitions is not valid: ' . $input);
        return $matches[1][0];
    }
    
    private function getPropertieTypeDefinedByUser(string $input) : array{
        $response = [];
        //Check if Type it was passed
        if(!preg_match_all('/^(.*?):(.*?)$/', $input, $matches))
            throw new DatabaseMapException('Constraint propertie definition is not valid!');
       
        $propertyType = $matches[2][0];

        //Check if it was specified length
        if(preg_match('/\((.{1,8})\)/', $propertyType, $matches)){
            $response['name'] = preg_replace('/\(.{1,8}\)/', '', $propertyType);
            $response['length'] = $matches[1];
        }
        else{
            $response['name'] = $propertyType;
            $response['length'] = 0; //default value
        }

         //Check if type exists
         $response['name'] = $this->propertyTypeIsDefined($response['name']);

         return $response;
    }


    private function propertyTypeIsDefined($input){
        foreach(self::$TYPES_ALLOWED as $typeDefined){
            if(strcasecmp($input, $typeDefined) == 0)
                return $typeDefined;
        }

        throw new DatabaseMapException("Column Type specified [$input] is not Defined!");
    }
    
    
    
}