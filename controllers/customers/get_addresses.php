<?php

require_once("PDOConnection.php");
function get_addresses($values)
{
    if(!(isset($values['user_id']) || isset($values['customer_id'])))
    {
        throw new Exception("Must provide user_id or customer_id");
    }
    $query = "SELECT a.id address_id,
                    a.last_updated,
                    name,
                    address1,
                    address2,
                    city,
                    state,
                    zipcode,
                    type 
                FROM addresses a ";
    if(isset($values['user_id']))
    {
        $query .= " JOIN user_addresses b ON a.id = b.address_id "
            . " WHERE b.user_id = :user_id ";
        $execArray = array('user_id' => (int)$values['user_id']);
    }
    elseif(isset($values['customer_id']))
    {
        $query .= " JOIN customer_addresses b ON a.id = b.address_id "
            . " WHERE b.customer_id = :customer_id";
        $execArray = array('customer_id' => (int)$values['customer_id']);
    }
    if(isset($values['address_id']))
    {
        $query .= " AND address_id = :address_id ";
        $execArray = array_merge($execArray,array('address_id' => (int)$values['address_id']));
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
    return array('addresses' => $addressArray);
}
