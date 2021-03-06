<?php

namespace Core;

use Core\Validation;

require_once('../config.php');

/*
|--------------------------------------------------------------------------
| @Class: Security
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
|
*/

class Security{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

private $ip;
private $time;
private $token;

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct(){
    $this->ip = getenv("REMOTE_ADDR");
    $this->time = date("Y-m-d H:i:s");
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getIp(){
    return $this->ip;
}

function getTime(){
    return $this->time;
}

function getToken(){
    return $this->token;
}

/*
|--------------------------------------------------------------------------
| @setMethods
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| @Function: newToken
|--------------------------------------------------------------------------
*/

function newToken(){
    $token = md5(date('HYisdm'));
    $this->token = $token;
    return $token;
}




}
