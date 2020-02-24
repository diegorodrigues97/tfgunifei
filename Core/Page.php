<?php

namespace Core;

use Core\Controller;
use Core\View;
use Core\Validation;

require_once('../config.php');

/*
|--------------------------------------------------------------------------
| @Class: Page
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
|
*/

class Page
{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

private $name;
private $title;
private $route;
private $controller;
private $view;
private $vars = [];
private $accessKeys = [];
private $requireLogin;

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct($params = false)
{
    $this->name = isset($params['name']) ? $params['name'] : false;
    $this->title = isset($params['title']) ? $params['title'] : false;
    $this->route = $params['route'];
    $this->controller = isset($params['controller']) ? $params['controller'] : false;
    $this->view = isset($params['view']) ? $params['view'] : false;
    $this->accessKeys = isset($params['accessKeys']) ? $params['accessKeys'] : [];
    $this->vars = isset($params['vars']) ? array_map(function(){return false;} ,array_flip($params['vars'])) : [];
    $this->requireLogin = isset($params['requireLogin']) ? $params['requireLogin'] : false;
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getName()
{
    return $this->name;
}

function getTitle()
{
    return $this->title;
}

function getRoute()
{
    return $this->route;
}


function getController()
{
    return $this->controller;
}

function getView()
{
    return $this->view;
}

function getVars()
{
    return $this->vars;
}

function getAccessKeys()
{
    return $this->accessKeys;
}

function getRequireLogin()
{
  return $this->requireLogin;
}

/*
|--------------------------------------------------------------------------
| @receiveVars
|--------------------------------------------------------------------------
*/

function receiveVars($vars = [])
{
  if(!empty(array_diff(array_keys($vars), array_keys($this->vars))))
  {
    return false;
  }

  foreach ($vars as $key => $value)
  {
    $this->vars[$key] = $value;
  }
  return true;
}


/*
|--------------------------------------------------------------------------
| @render
|--------------------------------------------------------------------------
*/

function render()
{
    //Firs, verify if is necessary login
    $app = Application::getApp();
    if($this->requireLogin == true && $app->getUser()->isLoggedIn() == false)
    {
      throw new \Exception("Access Danied!", 6001);
    }
    //Check, is necessary some key
    if(!$this->verifyKeys())
    {
      throw new \Exception('Access Danied!', 6002); 
    }
    //Call Controller or View
    if($this->controller)
    {
      return $this->renderController();
    }
    else
    {
      return $this->renderView();
    }
}

/*
|--------------------------------------------------------------------------
| @renderController
|--------------------------------------------------------------------------
*/

private function renderController()
{
    $controller = new Controller($this->controller, $this->vars);
    $controller->load();
}

/*
|--------------------------------------------------------------------------
| @renderView
|--------------------------------------------------------------------------
*/

private function renderView()
{
    $v = new View($this->view);
    $v->render();
}

/*
|--------------------------------------------------------------------------
| @verifyKeys
|--------------------------------------------------------------------------
*/

function verifyKeys()
{
  if(empty($this->accessKeys))
  {
    return true;
  }

  $app = Application::getApp();
  foreach($this->accessKeys as $key)
  {
    if(!$app->getUser()->hasKey($key))
      return false;
  }

  return true;
}

/*
|--------------------------------------------------------------------------
| @Function: callController
|--------------------------------------------------------------------------
*/

function callController(){
    if($this->controller != false)
        return true;
    else
        return false;
}




}
