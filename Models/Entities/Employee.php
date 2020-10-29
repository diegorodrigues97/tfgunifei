<?php

namespace Models\Entities;

class Employee extends User
{
    private int $Id;
    private string $Code;
    private decimal $Salary;
}