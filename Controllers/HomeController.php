<?php

use Core\View;

function Index(){
    $message = "Hello World!";

    $view = new View("Home", compact('message'));
    $view->render();
}

function Apresenta($vars){
    print_r($vars);
    $message = "ID: ";

    $view = new View("Home", compact('message'));
    $view->render();
}