<?php

function getConnection(){
    try{
        $conn = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=mercado', 'postgres','1234');
        if($conn) {
        return $conn;
        }
        }catch (PDOException $e){
        echo $e->getMessage();
        }
}