<?php

namespace Core\Database;

require_once("../config.php");

use PDO;

class Mysql extends PDO
{
	protected $conn;
	protected $errors;
	protected $lastId;

	public function __construct($params = false)
	{
		if(!$params)
		{
			$params = getAppConfig()['database'];
		}
		
		$this->conn = new \PDO("mysql:dbname={$params['dbname']};host={$params['host']}",
			$params['user'],
			$params['password'],
			array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
		);
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
