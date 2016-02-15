<?php
require_once("PDOConnection.php");
function get_users()
{
    $dbh = new PDOConnection();

    $query = "SELECT username, email FROM users ";
    $classArray = array();
    foreach($dbh->query($query) as $row)
    {
        $classArray[] = $row;
    }
    return $classArray;

}
