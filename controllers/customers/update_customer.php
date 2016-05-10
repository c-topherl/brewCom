<?php
/**/
include_once "common.inc";
require_once "PDOConnection.php";
function update_customer($customer)
{
    if(!isset($customer['id']))
    {
        throw new Exception('Must provide id');
    }
    if(!(isset($customer['code']) || isset($customer['name']) || isset($customer['active'])))
    {
        throw new Exception("Nothing changed!");
    }

    $dbh = new PDOConnection();
    $oldValues = CheckCustIdExists($dbh, $customer['id']);
    $customer['code'] = isset($customer['code']) ? $customer['code'] : $oldValues['code'];
    $customer['name'] = isset($customer['name']) ? $customer['name'] : $oldValues['name'];
    $customer['active'] = isset($customer['active']) ? $customer['active'] : $oldValues['active'];

    //TODO
    $query = "UPDATE customers SET code = :code, name = :name, active = :active WHERE id = :id";
    $sth = $dbh->prepare($query);

    $sth->bindParam(':id', $customer['id'], PDO::PARAM_INT);
    $sth->bindParam(':code', $customer['code']);
    $sth->bindParam(':name', $customer['name']);
    $sth->bindParam(':active', $customer['active']);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return $customer;
}
function CheckCustIdExists($dbh, $id)
{
    $query = "SELECT id,code,name,active,last_updated FROM customers WHERE id = :id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':id', $id, PDO::PARAM_INT);

    if(!($sth->execute()))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if(!($oldValues = $sth->fetch()))
    {
        throw new Exception("customer id: '".$id."' not found!");
    }
    return $oldValues;
}
