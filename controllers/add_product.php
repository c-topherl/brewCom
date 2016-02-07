<?php
require_once("DBConnection.php");
function addProduct(&$error = null)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
    $code = $_POST['code'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $class = $_POST['class'];
    if(check_product_exists($dbConn,$code))
    {
        $error = "Product code already exists!";
        return false;
    }
    if(!check_class_exists($dbConn,$class))
    {
        $error = "Product class does not exist!";
        return false;
    }
    $sql = "INSERT INTO products(description,code,price,class) VALUES('$description','$code',$price,'$class')";
    if(!($result = $dbConn->db_query($sql)))
    {
        echo mysqli_error($dbConn->get_con());
    }
    return $result;
}
//true mean product exists
function check_product_exists($dbConn,$code)
{
    $sql = "SELECT code FROM products WHERE code = '".mysqli_real_escape_string($dbConn->get_con(), $code)."'";
    if($result = $dbConn->db_query($sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
    }
    return false;
}
function check_class_exists($dbConn,$class_code)
{
    $sql = "SELECT class FROM product_classess WHERE code = '".mysqli_real_escape_string($dbConn->get_con(), $class_code)."'";
    if($result = $dbConn->db_query($sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
    }
    return false;
}
