<?php


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once('../config.php');


abstract class User
{
    protected int $id;
    public string $Name;
    protected string $LastName;
    protected string $Gender;
}

class Employee extends User
{
    function __construct()
    {
        $this->Name = "Luis Fernando";
    }
    
    protected int $id;
    protected string $Code;
    protected Department $department;
    protected array $Payments;
}

class Department{
    protected int $id;
    protected string $name;
}

class Payment{
    private int $id;
    private string $persistDate;
    private double $value;
}


use Core\Database\Map;

$map = new Map();
$map->addEntity('Employee')
                            ->constraint('id:int', ['pk', 'auto increment', 'unique'])
                            ->constraint('Name:varchar(255)', ['not null', 'default' => 'Diego'])
                            ->constraint('Gender:char(1)', ['not null', 'default' => 'M'])
                            ->addReference('department', 'Department');

echo "<pre>";
//print_r($map->getSchema());


$driver = __GetDependency("DatabaseDriver");
$dbParams = getAppConfig()['database'];
$driver->SetConnection($dbParams['name'], $dbParams['host'], $dbParams['user'], $dbParams['password']);
$driver->CreateOrUpdateDatabase($map->getSchema());
//print_r($databaseParams);