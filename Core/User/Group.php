<?php

namespace Core\User;

require_once('../config.php');

use Core\Validation;
use Core\Database\Mysql as Db;

class group
{

private $id;
private $name;
private $accessKeys;
private $date_criation;
private $date_lastUpdadete;

function __construct($id_group)
{
  $db = new Db();
  $data = $db->select("select * from system_user_group where id = :id", ['id'=>$id_group]);

  if(count($data) == 0)
  {
    throw new \Exception("User Group ID not found", 301);
  }

  $data = $data[0];
  $this->id = $data['id'];
  $this->name = $data['name'];
  $this->accessKeys = $data['accessKeys'];
  $this->date_criation = $data['date_criation'];
  $this->date_lastUpdadete = $data['date_lastUpdate'];
}

/*
|--------------------------------------------------------------------------
| @getters
|--------------------------------------------------------------------------
*/

function getId()
{
  return $this->id;
}

function getName()
{
  return $this->name;
}

function getAccessKeys()
{
  return $this->accessKeys;
}

function getDateCriation()
{
  return $this->date_criation;
}

function getDateLastUpdate()
{
  return $this->date_lastUpdadete;
}

/*
|--------------------------------------------------------------------------
| @setters
|--------------------------------------------------------------------------
*/

function setName($value)
{
  $db = new Db();
  $db->query("update system_user_group set name = :value, date_lastUpdate = now() where id = :id", ['value'=>$value, 'id'=>$this->id]);

  $this->name = $value;

  return true;
}

function setAccessKeys($value)
{
  $db = new Db();
  $db->query("update system_user_group set accessKeys = :value, date_lastUpdate = now() where id = :id", ['value'=>$value, 'id'=>$this->id]);

  $this->accessKeys = $value;

  return true;
}

/*
|--------------------------------------------------------------------------
| @keysAreValid
|--------------------------------------------------------------------------
*/

static function keysAreValid($keys)
{
  if(is_string($keys))
  {
    $keys = explode(',', $keys);
  }

  if(!\is_array($keys))
  {
    return ['error'=>true, 'message'=>'Invalid Input', 'keys'=>[]];
  }

  $keysAvailable = array_keys(getAppConfig()['app_keys']);
  $match = array_diff($value, $keysAvailable);
  if(count($match) > 0)
  {
    return ['error'=>true, 'message'=>'Invalid Keys: ' . json_encode($match), 'keys'=>[]];
  }

  return ['error'=>false, 'message'=>'', 'keys'=>$keys];
}

/*
|--------------------------------------------------------------------------
| @hasKeys
|--------------------------------------------------------------------------
*/

function hasKeys($keys)
{
  if(is_string($keys))
  {
    $keys = explode(',', $keys);
  }

  if(!\is_array($keys))
  {
    return ['error'=>true, 'message'=>'Invalid Input'];
  }

  foreach ($keys as $item)
  {
    if(!in_array($item, $this->accessKeys))
    {
      return ['error'=>true, 'message'=>"User doesn't have key: {$item}"];
    }

    return ['error'=>false, 'message'=>''];
  }

}

/*
|--------------------------------------------------------------------------
| @add
|--------------------------------------------------------------------------
*/

static function add($input = [])
{
  $filter = ["name", "accessKeys"];
  $isValid = Validation::isValid($input,$filter,"name");

  if(!$isValid['requiredFields'])
  {
    return $isValid;
  }

  if(isset($input['accessKeys']))
  {
    $isValid = $self::keysAreValid($input['accessKeys']);
    if($isValid['error'])
    {
      return $isValid;
    }
  }
  else
  {
    $input['accessKeys'] = null;
  }

  $input['accessKeys'] = isset($input['accessKeys']) ? $input['accessKeys'] : null;

  $db = new Db();
  $db->query("insert into system_user_group(name, accessKeys) values(:name, :accessKeys)", $input);

  return ['error'=>false, 'message'=>'', 'lastId'=>$db->lastId()];
}

/*
|--------------------------------------------------------------------------
| @rm
|--------------------------------------------------------------------------
*/

static function rm($id)
{
  $db = new Db();
  $usersActiveThisGroup =
  $db->select("select * from system_user where id_group = :id and isVisible = 'ON'", ['id'=>$id]);

  if(\count($usersActiveThisGroup) > 0)
  {
    return ['error'=>true, 'message'=>'This group could not be deleted because the are actives users on it!'];
  }

  $db->query("update system_user_group set isVisible = 'OFF' where id = :id", ['id'=>$id]);

  return ['error'=>false, 'message'=>'', 'lastId'=>$db->lastId()];
}

}
