<?php
require_once "PDOConnection.inc";
//At least needs ID passed in.  If you don't know it, get it from the get_products() routine
// This is so we can update a product code (since we store id on everything, don't change that)
function update_product($prodInfo)
{
    if(!isset($prodInfo['id']))
    {
        throw new Exception("Product id required.");
    }
    $dbh = new PDOConnection();
    $query = "SELECT id,code,description,price,active,last_updated FROM products WHERE id = :id";
    $sth = $dbh->prepare($query);
    $id = $prodInfo['id'];
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    if(!($sth->execute()))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    if(!($oldValues = $sth->fetch()))
    {
        throw new Exception("Product id: '".$id."' not found!");
    }
    $query = "UPDATE products 
        SET code = :code, 
            description = :description, 
            price = :price, 
            class = :class, 
            active = :active 
        WHERE id = :id";
    $sth = $dbh->prepare($query);

    $code = isset($prodInfo['code'])? $prodInfo['code'] : $oldValues['code'];
    $description = isset($prodInfo['description'])? $prodInfo['description'] : $oldValues['description'];
    $price = isset($prodInfo['price'])? $prodInfo['price'] : $oldValues['price'];
    $class = isset($prodInfo['class'])? $prodInfo['class'] : $oldValues['class'];
    $active = isset($prodInfo['active'])? $prodInfo['active'] : $oldValues['active'];

    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code);
    $sth->bindParam(':description', $description);
    $sth->bindParam(':price', $price);
    $sth->bindParam(':class', $class, PDO::PARAM_INT);
    $sth->bindParam(':active', $active, PDO::PARAM_INT);
    $sth->execute();
    return true;
}
