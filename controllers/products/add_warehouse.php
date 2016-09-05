<?php
require_once("PDOConnection.inc");
function add_warehouse($info)
{
    //TODO
    if(!(isset($info['code']) && isset($info['name'])))
    {
        throw new Exception("Must provide code and name");
    }
    $dbh = new PDOConnection();
    //Throws exception when exists
    CheckWarehouseCodeExists($dbh, $info['code']);

    $info = GetDefaultWarehouseInfo($info);
    $info['id'] = AddWarehouse($dbh, $info);
    return $info;
}

function CheckWarehouseCodeExists($dbh, $code)
{
    $query = "SELECT id,code FROM warehouses WHERE code = :code";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if($sth->rowCount() > 0)
    {
        throw new Exception("Warehouse code exists");
    }

    //false = warehouse code doesnt exist
    return FALSE;
}
function GetDefaultWarehouseInfo($info)
{
    $info += array('address1' => '', 'address2' => '', 'city' => '', 'state' => '', 'zipcode' => '', 'delivery_allowed' => 1, 'active' => 1);
    return $info;
}
function AddWarehouse($dbh, $info)
{
    $query = "INSERT INTO warehouses(code, name, address1, address2, city, state, zipcode, delivery_allowed, active) VALUES(:code, :name, :address1, :address2, :city, :state, :zipcode, :delivery_allowed, :active)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $info['code'], PDO::PARAM_STR);
    $sth->bindParam(':name', $info['name'], PDO::PARAM_STR);
    $sth->bindParam(':address1', $info['address1'], PDO::PARAM_STR);
    $sth->bindParam(':address2', $info['address2'], PDO::PARAM_STR);
    $sth->bindParam(':city', $info['city'], PDO::PARAM_STR);
    $sth->bindParam(':state', $info['state'], PDO::PARAM_STR);
    $sth->bindParam(':zipcode', $info['zipcode'], PDO::PARAM_STR);
    $sth->bindParam(':delivery_allowed', $info['delivery_allowed'], PDO::PARAM_STR);
    $sth->bindParam(':active', $info['active'], PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return $dbh->lastInsertId();
}
