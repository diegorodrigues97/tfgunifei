<?php


function Index(){
    $serv = new FuncionarioService();

    $view = new View("Login", $serv->GetFuncionarios());
    $view->render();
}
