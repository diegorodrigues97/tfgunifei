<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once('../config.php');

use Core\Application; 
use Core\View;
use Core\Template;
use Core\Exception\DatabaseMapException;

/*
|--------------------------------------------------------------------------
| @Section: initializes the application
|--------------------------------------------------------------------------
*/

if(!isset($_SESSION['app']))
{
	$_SESSION['app'] = serialize(new Application());
}
//$_SESSION['app'] = serialize(new Application());
/*
|--------------------------------------------------------------------------
| @Section: render page
|--------------------------------------------------------------------------
*/

//Get Url value
if(isset($_GET['url']))
{
	$route = "/" . $_GET['url'];
}
else
{
	$route = "/";
} 
 
$app = Application::getApp();

try
{
	$page = $app->getCurrentPage($route);

	//Call Controller or View
	if($page != false)
	{
		return $page->render();
	}
	else
	{
		throw new \Exception('Page not found: ' . $route, 1001);
	}
}
catch(DatabaseMapException $e)
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
