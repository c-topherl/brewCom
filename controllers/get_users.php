<?php
require_once("PDOConnection.php");
function get_users()
{
    $dbh = new PDOConnection();

    $query = "SELECT username, email FROM users ";
    $userArray = array();
    $sth = $dbh->prepare($query);
    $sth->execute();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $userArray[] = $row;
    }
    return array('users' => $userArray);
}
