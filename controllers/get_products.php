<?php
require_once("DBConnection.php");
function getProducts()
{
    $dbConn = new DBConnection();
    $sql = "SELECT p.description prod_desc, price, type, u.description unit_desc, u.abbreviation unit_abbrev, pt.description type_desc";
    $sql .= "FROM products p ";
    $sql .= "LEFT JOIN units u ON p.id = u.product_id ";
    $sql .= "LEFT JOIN product_types pt ON p.type = pt.id";
    $productArray = array();
    if($result = mysqli_query($$dbConn,$sql))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $productArray[] = $row;
        }
    }
    else
    {
        return false;
    }
    return $productArray;
}
