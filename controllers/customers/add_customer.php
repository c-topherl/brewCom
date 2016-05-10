<?php
/*
INPUTS:
    username
    email
    password
*/
require_once("PDOConnection.php"); 
require_once("common.inc"); //hash_password
function add_customer($custInfo)
{
    if(!(isset($custInfo['code']) && isset($custInfo['name'])))
    {
        throw new Exception("Requires code and name");
    }
    $custInfo['active'] = isset($custInfo['active']) ? $custInfo['active'] : 1;
    $dbh = new PDOConnection();
    CheckCustCodeExists($dbh, $custInfo['code']);
    $customer_id = AddToCustomers($dbh, $custInfo);
    $custInfo['id'] = $customer_id;
    return $custInfo;
}
function CheckCustCodeExists($dbh, $custCode)
{
    $query = 'SELECT id FROM customers WHERE code = :code';
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $custCode, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception('ERROR: add_customer CheckCodeExists: '.$sth->errorInfo()[2]);
    }
    return ($sth->rowCount() > 0);
}
function AddToCustomers($dbh, $custInfo)
{
    $query = 'INSERT INTO customers(code,name,active) VALUES(:code,:name,:active)';
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $custInfo['code'], PDO::PARAM_STR);
    $sth->bindParam(':name', $custInfo['name'], PDO::PARAM_STR);
    $sth->bindParam(':active', $custInfo['active'], PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception('ERROR: add_customer AddToCustomers '.$sth->errorInfo()[2]);
    }
    return $dbh->lastInsertId();
}
