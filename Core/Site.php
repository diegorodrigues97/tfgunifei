<?php

namespace Core;

use Core\Page;
use Core\Validation;

require_once('../config.php');

/*
|--------------------------------------------------------------------------
| @Class: Map
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
*/

class Site
{

/*
|--------------------------------------------------------------------------
| @Attributes
|--------------------------------------------------------------------------
*/

private $pages_historic = [];

static $page_params = ["route", "name", "title", "controller", "view", "accessKeys", "requireLogin", "vars"];
static $page_params_group = ["groupName", "groupRoute", "groupItems", "groupRequireLogin"];

/*
|--------------------------------------------------------------------------
| @Construct
|--------------------------------------------------------------------------
*/

function __construct(){}


/*
|--------------------------------------------------------------------------
| @Function: searchPage
|--------------------------------------------------------------------------
| @route param
| @route name or address
*/

private function searchPageParams($param, $value)
{
  foreach ($this->getPages() as $page)
  {
    if(isset($page[$param]) && $page[$param] == $value)
    {
      return $page;
    }
  }
  return false;
}

/*
|--------------------------------------------------------------------------
| @getListPages
|--------------------------------------------------------------------------
*/

function getPages()
{

    $listPages = include ADS_PUBLIC_HTML . "site.php";

    $pagesList = [];

    foreach ($listPages as $item)
    {
      if(empty(array_diff(array_keys($item), self::$page_params_group)))
      {
        foreach($item['groupItems'] as $page)
        {
          $page['route'] = $item['groupRoute'] . $page['route'];
          $page['requireLogin'] = $item['groupRequireLogin'];
          $page['groupName'] = $item['groupName'];
          $pagesList[] = $page;
        }
      }
      else
      {
        $pagesList[] = $item;
      }
    }

    return $pagesList;
}

/*
|--------------------------------------------------------------------------
| @getCurrentPage
|--------------------------------------------------------------------------
*/

function getCurrentPage($route = false)
{
    $page_name = false;
    //if route null, get url
    if(!$route)
    {
      $route = $_SERVER["REQUEST_URI"];
    }
    //Check if is a route name 
    if(preg_match('/&(.*?)&/', $route, $out))
    {
      $page_name = str_replace('','', $route);
    }
    //get params by route, else by route name
    if(!$page_name)
    {
      $routeParams = $this->searchPageParams("route", $route);
    }
    else
    {
      $routeParams = $this->searchPageParams("name", $page_name);
    }
    //check if found the params
    if(!$routeParams)
    {
      return false;
    }
    //load page object
    $page = new Page($routeParams);
    //load vars in object
    if(isset($this->pages_historic[$page->getName()]["vars"]))
    {
      $page->receiveVars($this->pages_historic[$page->getName()]["vars"]);
    }

    return $page;
}

/*
|--------------------------------------------------------------------------
| @getPageVariables
|--------------------------------------------------------------------------
*/

function setPageVariables($route, $vars = [])
{
  $page = $this->getCurrentPage($route);

  if(!$page)
  {
    return false;
  }

  $response = $page->receiveVars($vars);

  if(!$response)
  {
    return false;
  }

  $this->pages_historic[$page->getName()]["vars"] = $vars;

  return true;
}

}//endClass
