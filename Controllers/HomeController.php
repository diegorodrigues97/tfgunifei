<?php

use Core\View;

function Index(){
    $message = "Hello World!";

<<<<<<< HEAD
    $view = new View("Home", compact('message'));
    $view->render();
}

function Apresenta($vars){
    print_r($vars);
    $message = "ID: ";

    $view = new View("Home", compact('message'));
=======
    $view = new View("Home", compact('message'), true);
>>>>>>> 592361100eb64af51f310cc678c27e430040c1a3
    $view->render();
}