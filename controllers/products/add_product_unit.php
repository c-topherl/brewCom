<?php
/*
INPUTS:
product_code/product_id
unit_code/unit_id

note: passing id will be faster
*/
require_once("PDOConnection.php");
function add_product_class($info)
{
    $dbh = new PDOConnection();
    $product_id = isset($info['product_id']) ? $info['product_id'] : '';
    $unit_id = isset($info['unit_id']) ? $info['unit_id'] : '';
    $description = isset($info['description']) ? $info['description'] : '';
    if(!$product_id)
    {
        $product_code = isset($info['product_code']) ? $info['product_code'] : '';
        if(!$product_code)
        {
            throw new Exception("Product id or code required");
        }
        $query = "SELECT id FROM products WHERE code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code',$product_code);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        $product_id = $sth->fetchColumn();
    }
    if(!$unit_id)
    {
        $unit_code = isset($info['product_code']) ? $info['unit_code'] : '';
        if(!$unit_code)
        {
            throw new Exception("Unit id or code required");
        }
        $query = "SELECT id FROM units WHERE code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code',$unit_code);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo[2]);
        }
        $unit_id = $sth->fetchColumn();
    }

    $query = "SELECT id FROM product_unit WHERE product_id = :pid AND unit_id = :uid";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':pid', $product_id, PDO::PARAM_INT);
    $sth->bindParam(':uid', $unit_id, PDO::PARAM_INT);
    $sth->execute();
    if($sth->rowCount() > 0)
    {
        throw new Exception("Product/unit entry already exists.");
    }

    $query = "INSERT INTO product_unit(product_id,unit_id,description) VALUES(:pid,:uid,desc)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':pid', $product_id, PDO::PARAM_INT);
    $sth->bindParam(':uid', $unit_id, PDO::PARAM_INT);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return true;
}
