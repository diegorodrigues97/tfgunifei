<?php

use Core\View;

function Index(){
    
    // vars test
    $x = 20;
    $y = 30;
    $z = 5;
    $a = 4;


    $view = new View("Counter", compact('x', 'y', 'z', 'a'));

    $view->render();
}