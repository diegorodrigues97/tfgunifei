<?php

namespace Core\Database;

require_once("../config.php");

use PDO; 

class DbConnection
{
	protected $conn;
	protected $errors;
	protected $lastId;
	protected $CommandList = [];
	protected $Entity;
	protected $DbDriver;

	// public function __construct($entity, $dbDriver)
	// {
	// 	//Storage the Entity
	// 	$this->Entity = $entity;

	// 	//Storage Database Driver
	// 	$this->DbDriver = $dbDriver;

	// 	$params = getAppConfig()['database'];
		
	// 	$this->conn = new \PDO("mysql:dbname={$params['dbname']};host={$params['host']}",
	// 		$params['user'],
	// 		$params['password'],
	// 		array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
	// 	);
	// }

	function __construct()
	{
		$this->Entity = "";
		$this->DbDriver = "";
	}

	public function SelectAll()
	{
		$command = new DbCommand("SelectAll");
		array_push($this->CommandList, $command);
		return $this;
	}

	public function SelectFirst()
	{
		array_push($this->CommandList, ["SelectFirst"]);
		return $this;
	}

	public function WhereProperty($parameter)
	{
		$command = new DbCommand("WhereProperty", $parameter);
		array_push($this->CommandList, $command);
		return $this;
	}

	public function LikeTo($input)
	{
		array_push($this->CommandList, ["LikeTo", $input]);
		return $this;
	}
	
	public function EqualTo($parameter)
	{
		$command = new DbCommand("EqualTo", $parameter);
		array_push($this->CommandList, $command);
		return $this;
	}

	public function BiggerTo($input)
	{
		array_push($this->CommandList, ["BiggerTo", $input]);
		return $this;
	}
	
	public function BiggerAndEqualTo($input) 
	{
		array_push($this->CommandList, ["BiggerAndEqualTo", $input]);
		return $this;
	}

	public function LessTo($input)
	{
		array_push($this->CommandList, ["LessTo", $input]);
		return $this;
	}

	public function LessAndEqualTo($input)
	{
		array_push($this->CommandList, ["LessAndEqualTo", $input]);
		return $this;
	}

	public function And()
	{
		array_push($this->CommandList, ["And"]);
		return $this;
	}

	public function Or()
	{
		array_push($this->CommandList, ["Or"]);
		return $this;
	}

	public function OrderBy($input)
	{
		array_push($this->CommandList, ["OrderBy", $input]);
		return $this;
	}

	public function OrderByDesc($input)
	{
		array_push($this->CommandList, ["OrderByDesc", $input]);
		return $this;
	}

	public function Execute()
	{
		//First, check if the command list make sense, in the order it was passed.
		$this->CheckCommandCohesion();

		echo "<pre>";
		print_r($this);

		//Call the Driver responsible by to generate the query
		//$query = $this->DbDriver->GetQuery($this->CommandList, get_class($this->Entity));
	}


	
	
	
	private function CheckCommandCohesion()
	{
		return true;
	}
	
	
	
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

	private function setParams($statment, $parameters = array()){
		foreach ($parameters as $key => $value) {
			$this->setParam($statment,$key,$value);
		}
	}

	private function setParam($statment, $key, $value){
		$statment->bindParam($key,$value);
	}

	public function query($rawQuery, $params = array()){
		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		$this->errors = $stmt->errorInfo();
		$this->lastId = $this->conn->lastInsertId();
		$this->error();

		return $stmt;
	}

	public function select($rawQuery, $params = array()){

		$stmt = $this->query($rawQuery, $params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
