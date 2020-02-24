<?php

use Core\View;

function Index()
{
    //Script acessa o banco de dados e pega uma lista de usuários
    $users = [
        ["Id" => 1,"Name" => "Douglas", "Idade" => 23],
        ["Id" => 2,"Name" => "Douglas", "Idade" => 23],
        ["Id" => 3,"Name" => "Douglas", "Idade" => 23]
    ];

    $name = "Douglas";

    $view = new View("homepage", compact("name"));
    $view->render();
}
