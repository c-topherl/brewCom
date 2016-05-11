<?php
//id required
require_once "PDOConnection.php";
function update_warehouse($info)
{
    //TODO
    throw new Exception("Not implemented");

    if(!isset($info['id']))
    {
        throw new Exception("Product id required.");
    }
    $id = $info['id'];
    $dbh = new PDOConnection();
    CheckWarehouseIdExists($dbh, $info['id']);
    $info = GetDefaultWarehouseInfo($info);
    UpdateWarehouse($dbh, $info);
}
function GetOldWarehouseInfo($info)
{
    return $info;
}
function UpdateWarehouse($dbh, $info)
{
    return false;

    $query = "UPDATE warehouses 
        SET code = :code, 
            description = :description, 
            active = :active
                WHERE id = :id";
    $sth = $dbh->prepare($query);

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
