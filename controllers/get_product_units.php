<?php
require_once("PDOConnection.php");
//optional product info to read info by product code
//TODO finish optional parameters and binding paramters
function get_product_units($info = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT p.id product_id, p.code product_code, p.description product_description, p.price, pc.id class_id, pc.code class_code, pc.description class_description, u.id unit_id, u.code unit_code, u.description unit_description 
        FROM product_unit pu
        LEFT JOIN units u ON pu.unit_id = u.id 
        LEFT JOIN products p ON pu.product_id = p.id 
        LEFT JOIN product_classes pc ON p.class = pc.id ";
    $optionalParams = array();
    if(isset($info['product_code']))
    {
        $optionalParams[] = 'p.code = :product_code ';
        $product_code = $info['product_code'];
    }
    if(count($optionalParams) > 0)
    {
        $query .= "WHERE ";
        $query .= implode("AND ",$optionalParams);
    }
    $sth = $dbh->prepare($query);
    if(isset($product_code))
        $sth->bindParam(':product_code',$product_code);

    $sth->execute();
    $productArray = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $productArray[] = $row;
    }
    return array('product_units' => $productArray);
}
