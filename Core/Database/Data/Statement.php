<?php

namespace Core\Database\Data;

class Statement 
{
    public $Type;
    public $Parameter;    

    function __construct($type, $parameter = false)
    {
        $this->Type = $type;
        $this->Parameter = $parameter;
    }
}