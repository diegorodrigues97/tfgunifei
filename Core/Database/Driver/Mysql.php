<?php

namespace Core\Database\Driver;

use Core\Database\Data\Statement;
use Core\Database\Data\CStatement;

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

	public function SetConnectionParameters(string $name, string $host, string $user, string $password)
	{
		$this->conn = new \PDO("mysql:dbname={$name};host={$name}",
			$user,
			$pssword,
			array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
		);
	}

	public function GetDatabaseSchema() : Schema
	{

	}

	public function CreateOrUpdateDatabase(Schema $schema) : string
	{
		//Check tables

	}

	private function checkTableInDatabase(Table $table){
		//First, check if table exists in Database
		$data = $this->Select('SHOW FULL TABLES LIKE :table_name', ['table_name' => $this->table->getName()]);
		print_r($data);
		exit(1);
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
		$parameterName = "param" . $this->QueryParameters->count() + 1;
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
		$stmt = $this->conn->prepare($this->QueryString);

		$this->SetParams($stmt, $this->QueryParameters);

		$stmt->execute();

		$this->errors = $stmt->errorInfo();
		$this->lastId = $this->conn->lastInsertId();
		$this->error();

		return $stmt;
	}

	public function Select($rawQuery, $params = array()){

		$stmt = $this->Query($rawQuery, $params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
