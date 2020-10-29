<?php

namespace Drivers\Database;

use Core\Database\Data\Statement;
use Core\Database\Data\Schema;
use Core\Database\Data\Table;
use Core\Database\Data\Column;
use Core\Database\IDriver;
use Core\Database\Data\CStatement;
use Core\Exceptions\DatabaseException;
use PDOException;


require_once("../config.php");

use PDO;
use Core\Database\Data;

class Mysql extends PDO implements IDriver
{
	private $conn;
	private $errors;
	private $lastId;
	private $CommandList = [];
	private object $Entity;
	private ReflectionClass $EntityReflection;
	private string $QueryString;
	private $QueryParameters = [];

	function __construct(){

	}

	public function SetConnection(string $name, string $host, string $user, string $password)
	{
		try{
			$this->conn = new \PDO("mysql:dbname={$name};host={$host}",$user,$password,
			array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
		}
		catch(PDOException $err){
			throw new DatabaseException('Could not connected to the specified database!');
		}
		catch(Exception $err){
			throw new DatabaseException('Could not connected to the specified database!');
		}
	}

	public function GetDatabaseSchema() : Schema
	{

	}

	public function CreateOrUpdateDatabase(Schema $schema) : string
	{
		//Check tables
		foreach($schema->getTables() as $table){
			$this->checkTableInDatabase($table);
		}
	}

	private function checkTableInDatabase(Table $table){
		//Get table properties, if exists!
		$data = $this->Select('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table_name', ['table_name' => $table->getName()]);	

		$sql = null;
		
		if(count($data) == 0){
			$sql = $sql . $this->getSQLtoCreateTable($table);
		}
		else{
			$sql = $sql .$this->getSQLtoUpdateTable($table, $data);
		}
			
	}

	private function getSQLtoCreateTable(Table $table){
		$table_name = $table->getName();

		$query = "CREATE TABLE  `$table_name` ( \n";

		foreach($table->getColumns() as $column){
			if($column->isUserDefined() || strtoupper($column->getType()) == 'ARRAY'){
				continue;
			}				
			
			$query = $query . $this->getColumnConstraintInSQL($column);
		}

		$columnPrimaryKey = $table->getPrimaryKey();
		$column_name = $columnPrimaryKey->getName();
		$query = $query . "PRIMARY KEY (`$column_name`));\n";

		return $query;
	}

	private function getSQLtoUpdateTable(Table $table, $tableInfo){
		//Check if something it was changed
		

		exit(0);
	}

	private function tableItWasChanged(Table $table, $tableInfo) : bool{
		//Check all columns Table model
		foreach($table->getColumns() as $column){
			if($column->isUserDefined() || strtoupper($column->getType()) == 'ARRAY'){
				continue;
			}				
			
			$columnInfo = array_filter($tableInfo, fn($item) => $item['COLUMN_NAME'] == $column->getName());
			
			if(count($columnInfo) == 0)
				return true;

			//Check: TYPE
			if(strcasecmp($column->getType(), $columnInfo['DATA_TYPE']) != 0)
				return true;

			//Check: IS NULLABLE
			if(($column->isNotNull() == true && $columnInfo['DATA_TYPE'] == 'NO') || ($column->isNotNull() == false && $columnInfo['DATA_TYPE'] == 'YES'))
				return true;

			//Check: DEFAULT
			if(isNullOrEmpty($columnInfo['COLUMN_DEFAULT']) && $column->getConstraint()->hasDefault() == true)
				return true;

		}
	}

	private function isNullOrEmpty($value): bool{
		if($value == null || $value == '')
			return true;
		else
			return false;
	}

	private function getColumnConstraintInSQL(Column $column){
		$sql = '`' . $column->getName() . '` ' . $this->getEquivalentType($column->getType(), $column->getTypeLength());

		$constraint = $column->getConstraint();

		if($constraint->isNotNull()){
			$sql = $sql . " NOT NULL";
		}

		if($constraint->isUnique()){
			$sql = $sql . " UNIQUE";
		}

		if($constraint->isAutoIncrement()){
			$sql = $sql . " AUTO_INCREMENT";
		}

		if($constraint->hasDefault()){
			$sql = $sql . " DEFAULT '" . $constraint->getDefaultValue() . "'";
		}

		$sql = $sql . ",\n";

		return $sql;
	}

	private function getEquivalentType(string $type_name, string $type_length){
		$response = null;

		if($type_length == null || $type_length == ''){
			$type_length = '0';
		}

		switch(strtoupper($type_name)){
			case 'INT':
				if($type_length == 0 || $type_length == 4){
					$response = 'INT';
				}
				else if($type_length == 1){
					$response = 'TINYINT';
				}
				else if($type_length == 2){
					$response = 'SMALLINT';
				}
				else if($type_length == 3){
					$response = 'MEDIUMINT';
				}
				else if($type_length == 8){
					$response = 'BIGINT';
				}
			break;
			case 'DECIMAL':
				if($type_length == 0 || strpos($type_length, ',') == 0){
					$response = 'DECIMAL(5,2)';
				}
				else{
					$response = $type_length;
				}
			break;
			case 'FLOAT':
				$response = 'FLOAT';
			break;
			case 'DOUBLE':
				$response = 'DOUBLE';
			break;
			case 'VARCHAR':
				if($type_length == 0){
					$response = 'VARCHAR(125)';
				}
				else{
					$response = "VARCHAR($type_length)";
				}
			break;
			case 'STRING':
				$response = 'VARCHAR(125)';
			break;
			case 'CHAR':
				if($type_length == 0){
					$response = 'CHAR(1)';
				}
				else{
					$response = "CHAR($type_length)";
				}
			break;
			case 'TEXT':
				$response = 'TEXT';
			break;
			case 'BIT':
				$response = 'BIT';
			break;
			case 'DATETIME':
				$response = 'DATETIME';
			break;
		}

		return $response;
	}

	

	public function Pull(object $entity, $statements = []) 
	{
		//Clean all vars 
		Clean();
		//Set target Object
		SetEntity($entity);
		//Start mount query string
		$this->QueryString = "select * from $this->EntityReflection->getName() ";
		//Process all statements
		foreach($statements as $statement)
		{
			IncludeStatementInQuery($statement);
		}

		//Get prepare statement
		$prepareStatement = $this->GetPrepareStatement();

		return $prepareStatement->fechObjects($this->Entity);

	}

	private function AddStatementInQueryString(string $statement)
	{
		$this->QueryString += "$this->QueryString $statement ";
	}

	private function AddParameterInQueryString($parameter) : string
	{
		$parameterName = "param" . ($this->QueryParameters->count() + 1);
		$this->QueryParameters[$parameterName] = $parameter;

		return $parameterName;
	}

	private function QueryStringHasStatement(string $statement) : boolean
	{
		return preg_match("/^$statement$/i", $this->QueryString);
	}

	private function IncludeStatementInQuery(Statement $statement)
	{
		switch($statement->Type)
		{
			case CStatement::WHERE:
				if(!QueryStringHasStatement(CStatement::WHERE))
					AddStatementInQueryString("where $statement->Parameter");
				else
					AddStatementInQueryString("$statement->Parameter");
			break;
			case CStatement::EQUAL:
				AddStatementInQueryString("=" . AddParameterInQueryString($statement->Parameter));
			break;
			case CStatement::LIKE:
				AddStatementInQueryString("like %" . AddParameterInQueryString($statement->Parameter) . "%");
			break;
			case CStatement::BIGGERTO:
				AddStatementInQueryString(">" . AddParameterInQueryString($statement->Parameter));
			break;
			case CStatement::BIGGERANDEQUALTO:
				AddStatementInQueryString(">=" . AddParameterInQueryString($statement->Parameter));
			break;
			case CStatement::LESS:
				AddStatementInQueryString("<" . AddParameterInQueryString($statement->Parameter));
			break;
			case CStatement::LESSANDEQUALTO:
				AddStatementInQueryString("<=" . AddParameterInQueryString($statement->Parameter));
			break;
			case CStatement::AND:
				AddStatementInQueryString("and");
			break;
			case CStatement::OR:
				AddStatementInQueryString("or");
			break;
			case CStatement::PAGE:
				AddStatementInQueryString("limit $statement->Parameter");
			break;
		}
	}

	public function Delete(object $value) 
	{

	}

	public function Insert(object $value) 
	{

	}

	public function Update(object $value) 
	{

	}

	public function GetDatabaseName() : string 
	{
		return "MySQL Server";
	}

	private function SetEntity(object $entity)
	{
		$this->Entity = $entity;
		$this->EntityReflection = new ReflectionClass($entity);
	}

	private function Clean()
	{
		$this->QueryString = null;
		$this->QueryParameters = [];
	}
	
	/*-------------------------------------------------------------------------------- 
	|	DEFINITIONS
    ----------------------------------------------------------------------------------*/

	public function lastId(){
		return $this->lastId;
	}

	public function errorInfo(){
		$error =  $this->errors;
		if(!empty($error[2]))
			return array("error"=>true, "message"=>$error[2]);
		return array("error"=>false, "message"=>"");
	}

	public function error()
	{
		$error =  $this->errors;
		if(!empty($error[2]))
			throw new \Exception("Algo deu Errado!".$error[2]);
	}

	private function SetParams($statment, $parameters = array()){
		foreach ($parameters as $key => $value) {
			$this->SetParam($statment,$key,$value);
		}
	}

	private function SetParam($statment, $key, $value){
		$statment->BindParam($key,$value);
	}

	public function GetPrepareStatement($rawQuery, $params = array())
	{
		$stmt = $this->conn->prepare($rawQuery);

		$this->SetParams($stmt, $params);

		$stmt->execute();

		$this->errors = $stmt->errorInfo();
		$this->lastId = $this->conn->lastInsertId();
		$this->error();

		return $stmt;
	}

	public function Select($rawQuery, $params = []){

		$stmt = $this->GetPrepareStatement($rawQuery, $params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
