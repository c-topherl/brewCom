<?php
require_once("PDOConnection.php");
function get_users()
{
    $dbh = new PDOConnection();

    $query = "SELECT username, email FROM users ";
    $userArray = array();
    foreach($dbh->query($query) as $row)
    {
        $userArray[] = $row;
    }
    return $userArray;
}
