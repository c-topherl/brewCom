<?php
require_once("PDOConnection.php");
//optional product info to read info by code
//TODO finish optional parameters and binding paramters
function get_product_units($info = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT p.code prod_code, p.description prod_desc, p.price, pc.code class_code, pc.description class_desc, u.code unit_code, u.description unit_desc 
        FROM product_unit pu
        LEFT JOIN units u ON pu.unit_id = u.id 
        LEFT JOIN products p ON pu.product_id = p.id 
        LEFT JOIN product_classes pc ON p.class = pc.id ";
    $optionalParams = '';
    if(isset($info['code']))
    {
        $optionalParams .= 'p.code = :prod_code ';
        $code = $info['code'];
    }
    if($optionalParams !== '')
    {
        $query .= "WHERE ".$optionalParams;
    }
    echo $query;
    $productArray = array();
    foreach($dbh->query($query) as $row)
    {
        $productArray[] = $row;
    }
    return $productArray;
}
