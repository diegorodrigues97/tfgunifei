<?php

use Core\View;

function Index(){
    $message = "Hello World!";

    $view = new View("Home", compact('message'), true);
    $view->render();
}