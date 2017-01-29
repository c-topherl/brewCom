<?php
/*
code
description
*/
require_once("PDOConnection.inc");
function add_unit($unitArray)
{
    $dbh = new PDOConnection();
    $code = $unitArray['code'];
    $description = $unitArray['description'];

    $query = "SELECT id,code FROM units WHERE code = :code";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if($sth->rowCount() > 0)
    {
        throw new Exception("Unit code exists");
    }

    $query = "INSERT INTO units(code, description) VALUES(:code, :description)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return true;
}
