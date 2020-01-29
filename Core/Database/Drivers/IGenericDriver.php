<?php

namespace \Core\Database\Drivers;

Interface IGenericDriver
{
    public function GetDataBaseName();

    public function GetById();

    public function Select();
}