<?php

use Models\Entities;

$dbc = new DataBaseMap();

$dbc->addEntity('User')
                        ->setTableName('user')
                        ->constraint('Id:bigint', ['pk', 'unique', 'not null', 'auto increment'])
                        ->constraint('GrupoId:int', ['fk'=>'Group', 'not null'])
                        ->constraint('Gendle:char(1)', 'char(1)', ['default' => 'M', 'check' => 'function(val)=>{val.length == 1}', 'enum'=>'M;F'])
                        ->setReference('Payments', 'Payment', 'n');

