<?php
require_once("DBConnection.php");
function get_product_classes()
{
    $dbConn = new DBConnection();
    $sql = "SELECT *";
    $sql .= "FROM product_classes ";
    $classArray = array();
    $dbConn->db_connect();
    if($result = $dbConn->db_query($sql))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $classArray[] = $row;
        }
    }
    else
    {
        return false;
    }
    return $classArray;
}
