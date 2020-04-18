<?php

require_once('../config.php');

Use Core\Application;
Use Core\Request;
Use Core\Controller;
Use Core\View;

if(!isset($_POST))
{
  exit();
}

try
{
  $app = Application::getApp();
  $request = new Request($_POST);

  //Type: javascript route
  if($request->getType() == 'ROUTE')
  {
    //Save variables to route
    $response = $app->setPageVariables($request->getRoute(), $request->getVars());

    if(!$response)
    {
      echo json_encode([
        "error" => true,
        "message" =>" Route not found: {$request->getRoute()}"
      ]);
      exit();
    }

    $route = ($app->getCurrentPage($request->getRoute()))->getRoute();

    Application::setApp($app);

    echo json_encode([
      "error" => false,
      "message" => "",
      "route" => $route
    ]);
  }
  else //Type: call a controller
  {
    $controller = new Controller($request->getController(), $request->getVars());
    $controller->load();
  }

}
catch(Exception $e)
{
  $view = new View('exception', [
		'message'	=>	$e->getMessage(),
		'code'		=>	$e->getCode(),
		'file'		=>	$e->getFile(),
		'line'		=>	$e->getLine()
	]);
	$view->setTitle("Error");
	$view->render();
}
