<?php
require_once("PDOConnection.inc");
//optional product info to read info by code
//TODO add optional parameter support
function get_products($info = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT p.id product_id, p.code product_code, p.description product_desc, p.price, pc.id class_id, pc.code class_code, pc.description class_desc 
        FROM products p 
        LEFT JOIN product_classes pc ON p.class = pc.id ";
    $optionalParams = '';
    $code = '';
    if(isset($info['code']))
    {
        $optionalParams .= 'p.code = :prod_code ';
        $code = $info['code'];
    }
    if($optionalParams != '')
    {
        $query .= "WHERE " . $optionalParams;
    }
    $sth = $dbh->prepare($query);
    $paramArray = array(
        ':prod_code' => $code
    );
    $sth->execute($paramArray);
    $productArray = array();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $productArray[] = $row;
    }
    return array('products' => $productArray);
}
