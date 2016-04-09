<?php
require_once("PDOConnection.php");
function get_users($values = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT id, username, email FROM users ";

    if(isset($values['id']))
    {
        $optional .= "id = :id ";
    }

    if(isset($optional) && $optional !== '')
    {
        $query .= ' WHERE '.$optional;
    }
    $sth = $dbh->prepare($query);
    if(isset($values['id']))
    {
        $sth->bindParam(':id', $values['id'], PDO::PARAM_INT);
    }
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    $userArray = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $userArray[] = $row;
    }
    return array('users' => $userArray);
}
