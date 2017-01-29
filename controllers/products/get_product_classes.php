<?php
//TODO:
//classFilters to be expanded later
require_once("PDOConnection.inc");
function get_product_classes($classFilters = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT * FROM product_classes ";
    $classArray = array();
    $sth = $dbh->prepare($query);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $classArray[] = $row;
    }
    return $classArray;
}
