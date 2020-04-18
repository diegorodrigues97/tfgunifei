<?php

namespace Core;

use Core\Validation;


/*
|--------------------------------------------------------------------------
| @Class: Request
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
*/

class Request
{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

#types: CONTROLLER, ROUTE, DATA
private $type = false;
private $controller = false;
private $token = false;
private $vars = false;
private $route = false;

/*
|--------------------------------------------------------------------------
| @Cosntruct
|--------------------------------------------------------------------------
*/

function __construct($input)
{
    //Determine the request type: ROUTE, CONTROLLER
    if(isset($input['#route#']))
    {
      $this->type = 'ROUTE';
      $this->route = $input['route'];

      if(!isset($input['route']))
      {
        throw new \Exception("Param Route not found!", 1);
      }

      unset($input['route']);
      unset($input['#route#']);

      $this->vars = $input;
    }
    else
    {
      $this->type = 'CONTROLLER';

      if(!isset($input['token']))
      {
        throw new \Exception("Param TOKEN not found!", 1);
      }
      if(!isset($input['controller']))
      {
        throw new \Exception("Param CONTROLLER not found!", 1);
      }

      $this->token = $input['token'];
      $this->controller = $input['controller'];

      unset($input['token']);
      unset($input['controller']);

      $this->vars = $input;
    }
}

/*
|--------------------------------------------------------------------------
| @getMethods
|--------------------------------------------------------------------------
*/

function getType()
{
    return $this->type;
}

function getToken()
{
    return $this->token;
}

function getController()
{
    return $this->controller;
}

function getRoute()
{
    return $this->route;
}

function getVars()
{
    return $this->vars;
}

/*
|--------------------------------------------------------------------------
| @staticFunction: treatResponse
|--------------------------------------------------------------------------
*/

static function treatResponse($response, $pageName)
{
    if($response == false)
    {
      $response['error'] = false;
    }

    if($response['error'])
    {
		echo '<script>alert("' . $response['message'] . '"); history.back();</script>';
		exit();
	  }
    else
    {
		$app = Application::getApp();
		$page = $app->getCurrentPage("&" . $pageName .  "&");
		if(!$route)
			$route = '';
		return header("Location: " . $page->getRoute());
	  }
}


/*
|--------------------------------------------------------------------------
| @staticFunction: treatValidation
|--------------------------------------------------------------------------
*/

static function treatValidation($response)
{
	if($response['error'])
  {
    echo '<script>alert("' . $response['message'] . '"); history.back();</script>';
  }
	else
  {
    return true;
  }
}


}//endClass
