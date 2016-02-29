<?php
//TODO:
//filters to be expanded later
require_once("PDOConnection.php");
function get_units($filters = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT * FROM units ";
    $units = array();
    $sth = $dbh->prepare($query);
    $sth->execute();
    $result = $sth->fetchAll();
    foreach($result as $row)
    {
        $units[] = $row;
    }
    return $units;
}
