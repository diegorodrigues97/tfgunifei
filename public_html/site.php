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
        "controller" => "Home@Index",
        "name" => "Home",
<<<<<<< HEAD
        "title" => "Home",
    ],
    [
        "route" => "/Apresenta",
        "controller" => "Home@Apresenta",
        "name" => "Home",
        "title" => "Home",
        "vars" => ['id']
=======
        "title" => "Home"
    ],
    [
        "route" => "/about",
        "controller" => "About@Index",
        "name" => "About",
        "title" => "About"
    ],
    [
        "route" => "/counter",
        "controller" => "Counter@Index",
        "name" => "Counter",
        "title" => "Counter"
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
    ]
];
