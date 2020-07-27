<?php

use Core\View;

function Index(){
    
    $message = "About our company";

    $props = [["name" => "joseph"],["name" => "zlatan"],["name" => "gutierr"]];

    // $props = ['banana', 'laranja', 'manzana'];

    // $props = (object) $props;

    // $props = [0,1,2,3,7];

    // $view = new View("About", compact('message'));
    $view = new View("About", ['props'=>$props], false);

    $view->render();
}