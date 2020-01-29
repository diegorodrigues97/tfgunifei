<?php

namespace Core\Database;

class DbCommand 
{
    public $Name;
    public $Parameter;

    function __construct($name, $parameter = false)
    {
        $this->Name = $name;
        $this->Parameter = $parameter;
    }
}