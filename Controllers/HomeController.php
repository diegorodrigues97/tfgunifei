<?php

use Core\View;

function Index(){
    $message = "Hello World!";

    $view = new View("Home", compact('message'));
    $view->render();
}