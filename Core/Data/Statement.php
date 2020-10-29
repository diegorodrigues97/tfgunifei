<?php

namespace Core\Data;

class Statement 
{
    public $Type;
    public $Parameter;    

    function __construct($type, $parameter = false)
    {
        $this->Type = $type;
        $this->Parameter = $parameter;
    }

    public function getType()
    {
        return $this->Type;
    }

    public function getValue()
    {
        return $this->Parameter;
    }
}