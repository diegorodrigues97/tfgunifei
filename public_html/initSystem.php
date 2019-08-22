<?php

require_once("../config.php");

Use Core\System as System;
Use Core\User\User as User;

/*
Esta instrução cria o Banco de Dados com as tabelas essênciais do Sistema.
Por favor, antes de executar este script, verifique se as credenciais de acesso
ao Banco de Dados foram configuradas corretamente em <config.php>
*/
$response = System::init_database();

/*
Esta instrução cria um usuário administrador que tem acesso ao sistema
*/
$userParams = [
              "name"      =>  "admin",
              "lastName"  =>  "admin",
              "sexo"      =>  "M",
              "login"     =>  "admin",
              "email"     =>  "admin@admin",
              "password"  =>  "admin",
              "id_group"  =>  1
              ];
$response = User::add($userParams);
