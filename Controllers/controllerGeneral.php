<?php


/*
|--------------------------------------------------------------------------
| @section:
|--------------------------------------------------------------------------
*/

use Core\Database\Mysql as Db;
use Core\View as View;
use Core\Application as Application;
use Core\Request as Request;


function showFormLogin($input = [])
{

  $view = new View("Login");
  $view->render();
}

function showMainPaiel($input)
{

  if($input['id'] == null)
  {
    echo "Id inválido!";
  }
  else
  {
    echo "ID = {$input['id']}";
  }
}

function receiveLogin()
{
  echo "Hello World!";
}

function ckeckUserCredentials($input)
{
  $app = Application::getApp();
  $user = $app->getUser();
  $response = $user->login($input);
  Application::setApp($app);

  Request::treatResponse($response, "&main&");
}

/*
|--------------------------------------------------------------------------
| @section: getBreadcrumb
|--------------------------------------------------------------------------
*/

function getBreadcrumb(){

    $app = Application::getApp();
    $page = $app->getCurrentPage();

    if(!$page)
        return '<h6 style="color: red;"> Controller not faund! </h6>';

    $url = $page->getRoute();

    $comps = explode('/', $url);

    $i =1;
    $str_li = '';
    $href = '';

    foreach($comps as $comp){

        if($comp == null)
            continue;

        if($i == 1)
            $href = '/' . $comp;
        else
            $href = $href . '/' . $comp;

        $name = ucwords(str_replace('-',' ',$comp));

        if(count($comps) == $i)
            $str_li = $str_li . '<li class="breadcrumb-item active">' . $name .'</li>';
        else
            $str_li = $str_li . '<li class="breadcrumb-item"><a href="' . $href . '">' . $name .'</a></li>';

        $i++;
    }

    $code = '
    <nav aria-label="breadcrumb" style="margin-top: 20px;">
        <ol class="breadcrumb">
            ' . $str_li . '
        </ol>
    </nav>
    ';

    return $code;
}
