<?php

namespace Core;

require_once('../config.php');

use Core\User\User;
use Core\Site;
use Core\Page;
use Core\Security;
use Core\Validation;

/*
|--------------------------------------------------------------------------
| @Class: Application
|--------------------------------------------------------------------------
|
| @ClassFunction: Manger user access
|
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
*/


class Application
{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

#Objects
private $user;
private $security;
private $site;
#Arrays
private $page_indices = [];
private $box = [];

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct()
{
    $this->user = new User();
    $this->site = new Site();
    $this->security = new Security();
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getUser(){
    return $this->user;
}

function getSecurity(){
    return $this->security;
}

function getSite(){
    return $this->site;
}

function getBox(){
    return $this->box;
}

/*
|--------------------------------------------------------------------------
| @shortcuts
|--------------------------------------------------------------------------
*/

function getCurrentPage($route = null)
{
  return ($this->site)->getCurrentPage($route);
}


function setPageVariables($route, $vars = [])
{
  return $this->site->setPageVariables($route, $vars);
}

/*
|--------------------------------------------------------------------------
| @setMethods
|--------------------------------------------------------------------------
*/

function setSite($site){
    $this->site = $site;
}

function setBox($box = array()){
    $this->box = $box;
}

function setSitePage($page)
{
  return $this->site->setPage($page);
}

/*
|--------------------------------------------------------------------------
| @function: boxIds
|--------------------------------------------------------------------------
*/

function box_addId($name, $value){
    $this->boxIds[$name] = $value;
}

function box_getId($name){
    if(isset($this->boxIds[$name]))
        return $this->boxIds[$name];
    else
        return false;
}


/*
|--------------------------------------------------------------------------
| @function: getRoute
|--------------------------------------------------------------------------
*/

function getRouteByName($pageName){
  return $this->getCurrentPage("&".$pageName."&");
}



/*
|--------------------------------------------------------------------------
| @Privatefunction: checkKeys
|--------------------------------------------------------------------------
*/

private function checkKeys($accessKeysRequired = array()){
    foreach($accessKeysRequired as $key){
        $resp = $this->getUser()->hasKey($key);
        if(!$resp)
            throw new \Exception('Application@checkKeys: Acesso Negado!');
    }
    return true;
}

/*
|--------------------------------------------------------------------------
| @Privatefunction: commandInterpreter
|--------------------------------------------------------------------------
*/

private function commandInterpreter($command, $vars){
    $comp = explode('@', $command);
    $objName = $comp[0];
    $objMethod = $comp[1];
}

/*
|--------------------------------------------------------------------------
| @staticFunction: getApp
|--------------------------------------------------------------------------
*/

static function getApp()
{
	if(isset($_SESSION['app'])){
		return unserialize($_SESSION['app']);
	}
	return false;
}

/*
|--------------------------------------------------------------------------
| @staticFunction: setApp
|--------------------------------------------------------------------------
*/

static function setApp($app)
{
	$_SESSION['app'] = serialize($app);
}



}//endClass
