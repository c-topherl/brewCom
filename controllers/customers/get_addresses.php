<?php

require_once("PDOConnection.php");
function get_addresses($values = NULL)
{
    if(!(isset($values['user_id']) || isset($values['customer_id'])))
    {
        throw new Exception("Must provide user_id or customer_id");
    }
    $query = "SELECT a.id address_id, a.last_updated, a.name, address1, address2, city, state, zipcode, type FROM addresses a 
            LEFT JOIN user_addresses ua ON a.id = ua.address_id 
            LEFT JOIN customer_addresses ca ON a.id = ca.address_id 
        WHERE (ua.user_id = :user_id or ca.customer_id = :customer_id) ";

    $execArray['user_id'] = isset($values['user_id']) ? $values['user_id'] : -1;
    $execArray['customer_id'] = isset($values['customer_id']) ? $values['customer_id'] : -1;

    if(isset($values['address_id']))
    {
        $query .= " AND a.id = :address_id ";
        $execArray['address_id'] = (int)$values['address_id'];
    }

    if(isset($values['type']))
    {
        $query .= " AND type = :type ";
        $execArray['type'] = (int)$values['type'];
    }

    $dbh = new PDOConnection();
    $sth = $dbh->prepare($query);
    if(!$sth->execute($execArray))
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    $addressArray = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $addressArray[] = $row;
    }
    return $addressArray;
}

