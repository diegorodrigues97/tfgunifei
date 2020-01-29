<?php

use Core\View;

function Index()
{
  //Vai no DB
  $Nome = "Pedro";
  $Idade = 25;

  $View = new View("homepage", compact("Nome", "Idade"));
  $View->render();
}

function GeraIndice(){
  return "teste";

}
