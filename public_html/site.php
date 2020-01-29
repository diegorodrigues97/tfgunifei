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
    "controller"=>"Pedro@Index",
    "vars" => [],
    "accessKeys" =>[],
    "name" => "homepage",
    "title" =>"Homepage", 
    "requireLogin" => false
  ],

  [
    "route" => "/sobre",
    "view"=>"sobre",
    "vars" => ["id"],
    "accessKeys" =>[],
    "name" => "sobre",
    "title" =>"Sobre"
  ],

  [
    "route" => "/login",
    "controller"=>"General@showFormLogin",
    "vars" => ["id","id2"],
    "accessKeys" =>[],
    "name" => "login",
    "title" =>"Login"
  ],

  [
    "groupName" => "Panel",
    "groupRoute" => "/panel",
    "groupRequireLogin" => true,
    "groupItems" =>
    [
      [
        "route" => "",
        "name" => "main",
        "title" => "Main Panel",
        "controller" => "controllerGeneral@showMainPaiel",
        "vars" => ['id']
      ],
      [
        "route" => "/usuarios",
        "name" => "panel-users",
        "title" => "Main Panel",
        "controller" => "general@showUser",
      ]
    ]
  ]

];
