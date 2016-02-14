<?php
require_once("PDOConnection.php");
function add_product_class($classArray)
{
    $dbh = new PDOConnection();
    $code = $classArray['code'];
    $description = $classArray['description'];
    $query = "SELECT code FROM product_classes where code = '$code'";
    foreach($dbh->query($query) as $row)
    {
        return false;
    }
    $query = "INSERT INTO product_classes(description,code) VALUES('$description','$code')";
    return $dbh->query($query);
}
