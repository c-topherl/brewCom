<?php
require_once("PDOConnection.php");
function get_products()
{
    $dbh = new PDOConnection();
    $query = "SELECT p.code prod_code, p.description prod_desc, price, pc.code class_code, u.description unit_desc, u.abbreviation unit_abbrev, pc.description class_desc ";
    $query .= "FROM products p ";
    $query .= "LEFT JOIN units u ON p.id = u.product_id ";
    $query .= "LEFT JOIN product_classes pc ON p.class = pc.id ";
    $productArray = array();
    foreach($dbh->query($query) as $row)
    {
        $productArray[] = $row;
    }
    return $productArray;
}
