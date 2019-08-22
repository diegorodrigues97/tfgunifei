<?php

namespace Core\User;

require_once('../config.php');

use Core\Validation;
use Core\Database\Mysql as Sql;
use Core\User\Group as Group;

class User
{

/*
|--------------------------------------------------------------------------
| @Class: Admin
|--------------------------------------------------------------------------
|
| @ClassFunction: Manager  admin users
|
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|
*/

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

private $id;
private $group;
private $name;
private $lastName;
private $sexo;
private $login;
private $password;
private $email;
private $cellPhone;
private $date_criation;
private $date_lastUpdadete;
#security policies
private $isLoggedIn;

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct()
{
    //Inicialize object properties
    $this->isLoggedIn = false;
}

/*
|--------------------------------------------------------------------------
| @Function: isLoggedIn
|--------------------------------------------------------------------------
*/

function isLoggedIn()
{
    return $this->isLoggedIn;
}

/*
|--------------------------------------------------------------------------
| @Function: checkPassword
|--------------------------------------------------------------------------
*/

function checkPassword($password){
    if(!$this->isLoggedIn())
        return false;
    if(md5($password) === $this->getPassword())
        return true;
    else
        return false;
}

/*
|--------------------------------------------------------------------------
| @Function: hasKey
|--------------------------------------------------------------------------
*/

function hasKey($key)
{
  if($this->group->hasKeys($key)['error'])
  {
    return false;
  }
  else
  {
    return true;
  }
}

/*
|--------------------------------------------------------------------------
| @Function: login
|--------------------------------------------------------------------------
*/

function login($input)
{
    //Check input
    $filter = array('login', 'password');
    $v = Validation::isValid($input, $filter, 'login,password');
    if($v['error'] || !$v['requiredFields'])
        return $v;
    //Search user
    $sql = new Sql();
    $d = $sql->select('select * from system_user where login = :login and isVisible = "ON"', array('login'=>$input['login']));
    if(count($d) == 0)
        return array('error'=>true, 'message'=>'User@login: user not found!');
    //Verify if password is correct
    if(md5($input['password']) === $d[0]['password'])
    {
        //Load object properties
        $this->isLoggedIn = true;
        $this->id = $d[0]['id'];
        $this->name = $d[0]['name'];
        $this->lastName = $d[0]['lastName'];
        $this->sexo = $d[0]['sexo'];
        $this->login = $d[0]['login'];
        $this->password = $d[0]['password'];
        $this->email = $d[0]['email'];
        $this->cellPhone = $d[0]['cellPhone'];
        $this->date_criation = $d[0]['date_criation'];
        $this->date_lastUpdate = $d[0]['date_lastUpdate'];

        $this->group = new Group($d[0]['id_group']);

        return array('error'=>false, 'message'=>'');
    }
    else
    {
        return array('error'=>true, 'message'=>'User@login: password incorrect!');
    }
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getId(){
    return $this->id;
}

function getName(){
    return $this->name;
}

function getLastName(){
    return $this->lastName;
}

function getSexo()
{
  return $this->sexo;
}

function getLogin(){
    return $this->login;
}

function getPassword(){
    return $this->password;
}

function getEmail(){
    return $this->email();
}

function getCellPhone(){
    return $this->cellPhone;
}

function getGroupInfo()
{
  return $this->group_info;
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
| @setMethods
|--------------------------------------------------------------------------
*/

function setName($value)
{
    $sql = new Sql();
    $sql->query('update system_user set name = :value', array('value'=>$value));
    $this->name = $value;

    return true;
}

function setLastName($value)
{
    $sql = new Sql();
    $sql->query('update system_user set lastName = :value', array('value'=>$value));
    $this->lastName = $value;

    return true;
}

function setSexo($value)
{
    $options = ["M", "F"];
    if(!in_array($value, $options))
    {
      return ['error'=>true, 'message'=>'Invald Input!'];
    }
    $sql = new Sql();
    $sql->query('update system_user set sexo = :value', array('value'=>$value));
    $this->sexo = $value;

    return true;
}

function setLogin($value)
{
    $sql = new Sql();
    $sql->query('update system_user set login = :value', array('value'=>$value));
    $this->login = $value;

    return true;
}

function setPassword($value)
{
    $sql = new Sql();
    $passwordCript = md5($value);
    $sql->query('update system_user set password = :value', array('value'=>$passwordCript));
    $this->password = $passwordCript;

    return true;
}

function setEmail($value)
{
    $sql = new Sql();
    $sql->query('update system_user set email = :value', array('value'=>$value));
    $this->email = $value;

    return true;
}

function setCellphone($value)
{
    $sql = new Sql();
    $sql->query('update system_user set cellphone = :value', array('value'=>$value));
    $this->cellphone = $value;

    return true;
}


/*
|--------------------------------------------------------------------------
| @StaticFunction: add
|--------------------------------------------------------------------------
*/

static function add($input){
    //Check input
    $filter = array("name", "lastName", "sexo", "login", "email", "password", "id_group");
    $response = Validation::isValid($input, $filter);
    if($response['error'] || $response['miss'])
        return $response;
    //Check if login already exists
    $sql = new Sql();
    $d = $sql->select('select * from system_user where login = :login', array('login'=>$input['login']));
    if(count($d) > 0 )
        return array('error'=>true, 'message'=>'User@add: Login already exists!');
    $input['password'] = md5($input['password']);
    $sql->query('insert into system_user(name,lastName,sexo,login,email,password,id_group) values(:name,:lastName,:sexo,:login,:email,:password,:id_group)', $input);
    return array('error'=>false, 'message'=>'User@add:  User successfully registered!', 'lastId'=>$sql->lastId());
}

/*
|--------------------------------------------------------------------------
| @StaticFunction: rm
|--------------------------------------------------------------------------
*/

static function rm($id){
    $sql = new Sql();
    //Check if the user exists
    $d = $sql->select('select * from system_user where id = :id and isVisible = "ON"', array('id'=>$id));
    if(count($d) == 0)
        return array('error'=>true, 'message'=>'User@rm: User not found!');
    $sql->query('update system_user set isVisible = "OFF" where id = :id', array('id'=>$id));
    return array('error'=>false, 'message'=>'User@rm: User successfully removed');
}


}
