<?php

use Core\View;

function Index(){
    
    $message = "About our company";

    $props = [["name" => "joseph"],["name" => "zlatan"],["name" => "gutierr"]];

    $props = (object) $props;

    //$props = [["name" => "pedro"],["name" => "diego"], ["name" => "rebola"]];

    // $view = new View("About", compact('message'));
    $view = new View("About", ['props'=>$props]);

    $view->render();
}