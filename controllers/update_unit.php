<?php
require_once "PDOConnection.php";
include_once "get_units.php";
//At least needs ID passed in.  If you don't know it, get it from the get_products() routine
// This is so we can update a product code (since we store id on everything, don't change that)
function update_unit($unitInfo)
{
    if(!isset($unitInfo['id']))
    {
        throw new Exception("Product id required.");
    }
    $id = $unitInfo['id'];
    $dbh = new PDOConnection();
    $oldValues = get_units(array('id' => $id))[0]; //returns array of units
    if(empty($oldValues))
    {
        throw new Exception("Product id: '".$id."' not found!");
    }

    $query = "UPDATE units 
        SET code = :code, 
            description = :description, 
            active = :active
        WHERE id = :id";
    $sth = $dbh->prepare($query);

    $code = isset($unitInfo['code'])? $unitInfo['code'] : $oldValues['code'];
    $description = isset($unitInfo['description'])? $unitInfo['description'] : $oldValues['description'];
    $active = isset($unitInfo['active']) ? $unitInfo['active'] : $oldValues['active'];

    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code);
    $sth->bindParam(':description', $description);
    $sth->bindParam(':active', $active, PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return true;
}
