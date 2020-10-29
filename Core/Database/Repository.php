<?php

namespace Core\Database;

require_once("../config.php");

use PDO; 
use Core\AppException;
use Core\Database\Data\CStatement;
use Core\Database\Data\Statement;
use Core\Database\Data\Schema;

abstract class Repository implements IRepository
{
	protected $Statements = [];
	protected object $Entity;
	protected IDriver $Driver;

	
	

	public function __construct($entity)
	{
		
		//Get Database Driver by Dependency Injection
		$this->Driver = __GetDependency("DatabaseDriver");	
		
		//Set Database Connection parameters
		try
		{
			//Get database connection parameters
			$dbParams = getAppConfig()['database'];
			//Try conect to the Database
			$this->Driver->SetConnection(
			$dbParams['name'],
			$dbParams['host'],
			$dbParams['user'],
			$dbParams['password']);
		}
		catch(Exception $err)
		{
			throw new AppException("Database Connections Parameters invalid!");
		}

	}

	private function CheckObjectModelPropertie($propertie)
	{
		if(!\get_class_vars($this->ObjectModel)[$parameter])
			throw new AppException("Database OnPropertie invalid.");
	}

	/*-------------------------------------------------------------------------------- 
	|	DEFINITIONS
	----------------------------------------------------------------------------------*/

	public abstract function SetEntity(object $entity);
	
	/*-------------------------------------------------------------------------------- 
	|	STATEMENTS
	----------------------------------------------------------------------------------*/

	public function Where(string $value)
	{
		$this->Statements[] = new DbCommand(CStatement::WHERE, $value);			
		return $this;
	}

	
	public function And(string $value)
	{
		$this->Statements[] = new DbCommand(CStatement::AND, $value);			
		return $this;
	}

	public function Or(string $value)
	{
		$this->Statements[] = new DbCommand(CStatement::OR, $value);			
		return $this;
	}

	public function Equal(string $value)
	{
		$this->Statements[] = new DbCommand(CStatement::EQUAL, $value);			
		return $this;
	}

	public function Like(string $value)
	{
		$this->Statements[] = new DbCommand(CStatement::LIKE, $value);			
		return $this;
	}

	public function BiggerTo($value)
	{
		$this->Statements[] = new DbCommand(CStatement::BIGGERTO, $value);			
		return $this;
	}

	public function BiggerAndEqualTo($value)
	{
		$this->Statements[] = new DbCommand(CStatement::BIGGERANDEQUALTO, $value);			
		return $this;
	}
	
	public function LesstTo($value)
	{
		$this->Statements[] = new DbCommand(CStatement::LESSTO, $value);			
		return $this;
	}

	public function LesstAndEqualTo($value)
	{
		$this->Statements[] = new DbCommand(CStatement::LESSANDEQUALTO, $value);			
		return $this;
	}

	public function Page(int $itemsPerPage, int $currentPage)
	{
		$offset = $itemsPerPage * $currentPage;

		$this->Statements[] = new DbCommand(CStatement::PAGE, "$offset,$itemsPerPage");			
		return $this;
	}

	/*-------------------------------------------------------------------------------- 
	|	ACTIONS
	----------------------------------------------------------------------------------*/

	public function ToList()
	{
		
	}
	
	public function Insert(object $entity)
	{
		
	}

	public function Update(object $entity)
	{
		
	}

	public function Delete(object $entity)
	{
		
	}

	/*-------------------------------------------------------------------------------- 
	|	DATABASE MANAGEMENT
	----------------------------------------------------------------------------------*/

	public function UpdateDatabaseSchema(Schema $value)
	{
		
	}
}
