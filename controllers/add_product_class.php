<?php
require_once("DBConnection.php");
function add_product_class($classArray)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
    $code = $classArray['code'];
    $description = $classArray['description'];
    $sql = "SELECT code FROM product_classes where code = '".mysqli_real_escape_string($dbConn->get_con(), $code)."'";
    if($result = $dbConn->db_query($sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            return false;
        }
        $sql = "INSERT INTO product_classes(description,code) VALUES('$description','$code')";
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
