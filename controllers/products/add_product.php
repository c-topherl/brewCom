<?php
/*
INPUTS :
code
description
price
class
*/
require_once("PDOConnection.inc");
function add_product($productArray)
{
    $dbh = new PDOConnection();
    $code = $productArray['code'];
    $description = $productArray['description'];
    $price = $productArray['price'];
    $class_id = $productArray['class_id'];
    if(check_product_exists($dbh,$class_id))
    {
        throw new Exception("Product code already exists");
    }
    $class_id = get_class_id($dbh,$class_id);
    $query = "INSERT INTO products(description,code,price,class) VALUES(:description,:code,:price,:class_id)";
    $sth = $dbh->prepare($query);
    $parameters = array(':description' => $description, 
        ':code' => $code, 
        ':price' => $price, 
        ':class_id' => $class_id);
    if(!$sth->execute($parameters))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return array('id' => $dbh->lastInsertId());
}
//true mean product exists
function check_product_exists($dbh,$code)
{
    $query = "SELECT code FROM products WHERE code = :code";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->execute();
    return ($sth->rowCount() > 0);
}
function get_class_id($dbh,$class_id)
{
    $query = "SELECT id FROM product_classes WHERE id = :class_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':class_id', $class_id);
    $sth->execute();
    if($sth->rowCount() <= 0)
    {
        throw new Exception("Product class id: ".$class_id." does not exist!");
    } 
    $row = $sth->fetch();
    return $row['id'];;
}
