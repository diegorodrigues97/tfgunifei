<?php

namespace Core\Exceptions;

class ArrayListException extends Exception
{
    public function __construct(string $message, string $code = '', string $file = '', string $line = ''){
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
    }
}