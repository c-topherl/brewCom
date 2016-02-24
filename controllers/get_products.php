<?php
require_once("PDOConnection.php");
//optional product info to read info by code
function get_products($productInfo = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT p.code prod_code, p.description prod_desc, price, pc.code class_code, u.description unit_desc, u.code unit_abbrev, pc.description class_desc ";
    $query .= "FROM products p ";
    $query .= "LEFT JOIN units u ON p.id = u.product_id ";
    $query .= "LEFT JOIN product_classes pc ON p.class = pc.id ";
    $query .= get_optional_params($productInfo);
    $optionalParams = '';
    if(isset($productInfo['code']))
    {
        $optionalParmas .= 'code = :prod_code ';
        $code = $productInfo['code'];
    }

    $productArray = array();
    foreach($dbh->query($query) as $row)
    {
        $productArray[] = $row;
    }
    return $productArray;
}
