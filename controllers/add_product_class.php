<?php
/*
INPUTS:
code
description
*/
require_once("PDOConnection.php");
function add_product_class($classArray)
{
    $dbh = new PDOConnection();
    $code = $classArray['code'];
    $description = $classArray['description'];

    $query = "SELECT code FROM product_classes where code = :code";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->execute();
    if($sth->rowCount() > 0)
    {
        throw new Exception("Class code exists");
    }

    $query = "INSERT INTO product_classes(description,code) VALUES(:description,:code)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    return $sth->execute();
}
