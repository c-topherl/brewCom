<?php
require_once "PDOConnection.php";
//At least needs ID passed in.  If you don't know it, get it from the get_product_classes() routine
// This is so we can update a product code (since we store id on everything, don't change that)
function update_product_class($classArray)
{
    $dbh = new PDOConnection();
    $query = "SELECT id,code,description,last_updated FROM product_classes WHERE id = :id";
    $sth = $dbh->prepare($query);
    $id = $classArray['id'];
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    if(!($oldValues = $sth->fetch()))
    {
        throw new Exception("Class id: '".$id."' not found!");
    }

    $query = "UPDATE product_classes SET code = :code, description = :description WHERE id = :id";
    $sth = $dbh->prepare($query);
    $code = isset($classArray['code'])? $classArray['code'] : $oldValues['code'];
    $description = isset($classArray['description'])? $classArray['description'] : $oldValues['description'];
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code);
    $sth->bindParam(':description', $description);
    $sth->execute();
    return true;
}
