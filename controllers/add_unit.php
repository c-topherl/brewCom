<?php
require_once("PDOConnection.php");
function add_unit($unitArray)
{
    $dbh = new PDOConnection();
    $product_id = $unitArray['product_id'];
    $code = $unitArray['code'];
    $description = $unitArray['description'];

    $query = "SELECT id,product_id,code FROM units WHERE product_id = :product_id AND code = :code";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->execute();
    if($sth->rowCount() > 0)
    {
        throw new Exception("Product/Unit code exists");
    }

    $query = "INSERT INTO units(product_id,description,code) VALUES(:product_id,:description,:code)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $sth->bindParam(':code', $code, PDO::PARAM_STR);
    $sth->bindParam(':description', $description, PDO::PARAM_STR);
    return $sth->execute();
}
