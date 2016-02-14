<?php
require_once("PDOConnection.php");
function add_product($productArray, &$error = null)
{
    $dbh = new PDOConnection();
    $code = $productArray['code'];
    $description = $productArray['description'];
    $price = $productArray['price'];
    $class = $productArray['class'];
    if(check_product_exists($dbh,$code))
    {
        $error = "Product code already exists!";
        return false;
    }
    if(!check_class_exists($dbh,$class))
    {
        $error = "Product class does not exist!";
        return false;
    }
    $query = "INSERT INTO products(description,code,price,class) VALUES('$description','$code',$price,'$class')";
    return $dbh->query($query);
}
//true mean product exists
function check_product_exists($dbh,$code)
{
    $query = "SELECT code FROM products WHERE code = '$code'";
    foreach($dbh->query($query) as $row)
    {
        return true;
    }
    return false;
}
function check_class_exists($dbh,$class_code)
{
    $query = "SELECT class FROM product_classess WHERE code = '$class_code'";
    foreach($dbh->query($query) as $row)
    {
        return true;
    }
    return false;
}
