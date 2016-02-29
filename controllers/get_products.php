<?php
require_once("PDOConnection.php");
//optional product info to read info by code
//TODO add optional parameter support
function get_products($info = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT p.code prod_code, p.description prod_desc, price, pc.code class_code, pc.description class_desc 
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
    echo $query."\n";
    $sth->execute($paramArray);
    $productArray = array();
    $result = $sth->fetchAll();
    foreach($result as $row)
    {
        $productArray[] = $row;
    }
    return $productArray;
}
