<?php

/*
|--------------------------------------------------------------------------
| @File: site
| @ClassAuthor: Diego Rodrigues da Silva
| @ClassAuthorEmail: diegorodrigues97@yahoo.com.br
|--------------------------------------------------------------------------
| This file is where list all pages of site.
|
|--------------------------------------------------------------------------
*/

require_once('../config.php');

/*
|--------------------------------------------------------------------------
| @Section: pages of site
|--------------------------------------------------------------------------
*/


return [

  [
    "route" => "/",
    "controller"=>"Homepage@Index",
    "vars" => [],
    "accessKeys" =>[],
    "name" => "homepage",
    "title" =>"Homepage", 
    "requireLogin" => false
  ]
];
