<?php
require_once("DBConnection.php");
function addProduct()
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
    $code = $_POST['code'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $sql = "SELECT code FROM products where code = '".mysqli_real_escape_string($dbConn->get_con(), $code)."'";
    if($result = $dbConn->db_query($sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            return false;
        }
        $sql = "INSERT INTO products(description,code,price,type) VALUES('$description','$code',$price,'$type')";
        if(!($result = $dbConn->db_query($sql)))
        {
            echo mysqli_error($dbConn->get_con());
        }
        return $result;
    }
    else
    {
        return false;
    }
}
