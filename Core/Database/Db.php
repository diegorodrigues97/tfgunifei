<?php

namespace Core\Database;

require_once ("../config.php");

use Core\Database\Mysql as Mysql;

/*
|--------------------------------------------------------------------------
| @Class: System
| @author: Diego Rodrigues da Silva
| @authorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
| This class manage all database and verify what use
*/

class Db
{

private $params = [];

function __construct()
{
  $this->params = getAppConfig()['database'];
}

function getDriver()
{
  return $this->params['driver'];
}

function getDb()
{
  if($this->params['driver'] == "mysql")
  {
    return new Mysql($this->params);
  }
}

function loadSystemTables()
{
  if($this->getDriver() == "mysql")
  {
    if(!\file_exists(ADS_DATABASE_SCRIPTS . "mysql-essential.script"))
    {
      throw new \Exception("Script MySql Essential not found!", 1);
    }
    $script = \file_get_contents(ADS_DATABASE_SCRIPTS . "mysql-essential.script");


    (new Mysql($this->params))->query($script);
    return ['error'=>false, 'message'=>''];
  }
}



}
