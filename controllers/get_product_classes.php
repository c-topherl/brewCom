<?php
require_once("PDOConnection.php");
function get_product_classes()
{
    $dbh = new PDOConnection();

    $query = "SELECT * FROM product_classes ";
    $classArray = array();
    foreach($dbh->query($query) as $row)
    {
        $classArray[] = $row;
    }
    return $classArray;

}
