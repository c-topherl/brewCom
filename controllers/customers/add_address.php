<?php
/*
INPUTS:
name
address1
address2 optional
city
state
zipcode
type - (0) billing / (1) shipping
*/
require_once "PDOConnection.inc";
function add_address($addressInfo)
{
    if(!(isset($addressInfo['name'])
        && isset($addressInfo['address1'])
        && isset($addressInfo['city'])
        && isset($addressInfo['state'])
        && isset($addressInfo['zipcode'])
        && isset($addressInfo['type'])
        && (isset($addressInfo['customer_id']) || isset($addressInfo['user_id']))))
    {
        throw new Exception("ERROR: name, address1, city, state, zipcode, type, and customer_id or user_id required");
    }

    $addressInfo['address2'] = isset($addressInfo['address2']) ? $addressInfo['address2'] : '';

    $dbh = new PDOConnection();
    $address_id = AddAddress($dbh, $addressInfo);

    if(isset($addressInfo['user_id']))
    {
        $query = "INSERT INTO user_addresses(user_id,address_id) VALUES(:user_id, :address_id)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':user_id', $addressInfo['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception("ERROR: could not add user address - " . $sth->errorInfo()[2]);
        }
    }
    elseif(isset($addressInfo['customer_id']))
    {
        $query = "INSERT INTO customer_addresses(customer_id,address_id) VALUES(:customer_id, :address_id)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':customer_id', $addressInfo['customer_id'], PDO::PARAM_INT);
        $sth->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception("ERROR: could not add customer address - " . $sth->errorInfo()[2]);
        }
    }

    return array('address_id' => $address_id);
}

function AddAddress($dbh, $addressInfo)
{

    $query = "INSERT INTO addresses(name, address1, address2, city, state, zipcode, type) "
        . "VALUES(:name, :address1, :address2, :city, :state, :zipcode, :type)";

    $sth = $dbh->prepare($query);

    $sth->bindParam(':name', $addressInfo['name']);
    $sth->bindParam(':address1', $addressInfo['address1']);
    $sth->bindParam(':address2', $addressInfo['address2']);
    $sth->bindParam(':city', $addressInfo['city']);
    $sth->bindParam(':state', $addressInfo['state']);
    $sth->bindParam(':zipcode', $addressInfo['zipcode']);
    $sth->bindParam(':type', $addressInfo['type'], PDO::PARAM_INT);

    if(!$sth->execute())
    {
        throw new Exception("ERROR: could not add address - " . $sth->errorInfo()[2]);
    }

    return $dbh->lastInsertId();
}
