<?php
require_once("PDOConnection.php");
function get_units($filters = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT id, code, description, active, last_updated FROM units ";
    $query .= GetOptionalParams($filters);
    $units = array();
    $sth = $dbh->prepare($query);
    if(isset($filters['id']))
    {
        $sth->bindParam(':id', $filters['id'], PDO::PARAM_INT);
    }
    elseif(isset($filters['code']))
    {
        $sth->bindParam(':code', $filters['code']);
    }

    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $units[] = $row;
    }
    return $units;
}
/*
build optional query string
TODO:
filters to be expanded later i.e. code/active
*/
function GetOptionalParams($filters)
{
    $query = '';
    if(isset($filters['id']))
    {
        $query .= "WHERE id = :id";
    }
    elseif(isset($filters['code']))
    {
        $query .= "WHERE code = :code";
    }
    return $query;
}
