<?php
require_once("DBConnection.php");
function get_products()
{
    $dbConn = new DBConnection();
    $sql = "SELECT p.code prod_code, p.description prod_desc, price, class, u.description unit_desc, u.abbreviation unit_abbrev, pc.description class_desc ";
    $sql .= "FROM products p ";
    $sql .= "LEFT JOIN units u ON p.id = u.product_id ";
    $sql .= "LEFT JOIN product_classes pc ON p.class = pc.code ";
    $productArray = array();
    $dbConn->db_connect();
    if($result = $dbConn->db_query($sql))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $productArray[] = $row;
        }
    }
    else
    {
        echo $dbConn->db_error();
        return false;
    }
    return $productArray;
}
