<?php
//TODO:
//classFilters to be expanded later
require_once("PDOConnection.php");
function get_product_classes($classFilters = NULL)
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
