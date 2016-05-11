<?php
include_once('PDOConnection.php');
function get_warehouses($opts = NULL)
{
    $dbh = new PDOConnection();

    //not supported yet
    $customer_id = isset($opts['customer_id']) ? $opts['customer_id'] : '';

    //not supported yet
    $query = "SELECT id, code, name, address1, address2, city, state, zipcode, delivery_allowed, active, last_updated FROM warehouses ";
    if(isset($opts['id']))
    {
        $query .= " WHERE id = :id";
    }

    $sth = $dbh->prepare($query);

    if(isset($opts['id']))
    {
        $sth->bindParam(':id', $opts['id'], PDO::PARAM_INT);
    }

    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    $warehouses = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $warehouses[] = $row;
    }
    return $warehouses;
}
