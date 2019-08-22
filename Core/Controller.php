<?php

namespace Core;

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

class Controller
{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

private $controller_name;
private $controller_function;
private $controller_vars = array();

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct($controller, $vars = array())
{
    try
    {
        $comp = explode('@', $controller);
        $this->controller_name = $comp[0];
        $this->controller_function = $comp[1];

        $this->controller_vars = $vars;
    }
    catch(Exception $e)
    {
        throw new \Exception('Invalid controller: ' . $controller);
    }
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getControllerName()
{
    return $this->controller_name;
}

function getControllerFunction()
{
    return $this->controller_function;
}

function getVars()
{
    return $this->controller_vars;
}

/*
|--------------------------------------------------------------------------
| @Function: load
|--------------------------------------------------------------------------
*/

function load()
{

  if(!is_file(ADS_CONTROLLERS . $this->controller_name . '.php'))
  {
    throw new \Exception("Invalid Controller Name: " . $this->controller_name, 1002);
  }

  require_once ADS_CONTROLLERS . $this->controller_name . '.php';

  if(!is_callable($this->controller_function))
  {
    throw new \Exception("Invalid Controller Function: " . $this->controller_function, 1003);
  }

  call_user_func($this->controller_function, $this->controller_vars);
}

}//endClass
