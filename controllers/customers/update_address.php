<?php
/*
INPUTS:
customers_id/user_id
name
address1
address2 optional
city
state
zipcode
type - (0) billing / (1) shipping
*/
require_once "PDOConnection.inc";
require_once "customers/get_addresses.php";
function update_address($addressInfo)
{
    if(!(isset($addressInfo['address_id']) && (isset($addressInfo['customer_id']) || isset($addressInfo['user_id']))))
    {
        throw new Exception("ERROR: address_id and customer_id or user_id required");
    }

    $oldInfo = get_addresses($addressInfo)[0]; //takes customer/user id and address id
    if(empty($oldInfo))
    {
        throw new Exception("Could not find address id for customer or user.");
    }
    $addressInfo = array_replace($oldInfo,$addressInfo);

    $query = "UPDATE addresses SET name = :name, address1 = :address1, address2 = :address2, city = :city, state = :state, zipcode = :zipcode, type = :type WHERE id = :address_id";
    $dbh = new PDOConnection();
    $sth = $dbh->prepare($query);

    $sth->bindParam(':name', $addressInfo['name']);
    $sth->bindParam(':address1', $addressInfo['address1']);
    $sth->bindParam(':address2', $addressInfo['address2']);
    $sth->bindParam(':city', $addressInfo['city']);
    $sth->bindParam(':state', $addressInfo['state']);
    $sth->bindParam(':zipcode', $addressInfo['zipcode']);
    $sth->bindParam(':type', $addressInfo['type'], PDO::PARAM_INT);
    $sth->bindParam(':address_id', $addressInfo['address_id'], PDO::PARAM_INT);

    if(!$sth->execute())
    {
        throw new Exception("ERROR: could not update address - " . $sth->errorInfo()[2]);
    }

    return $addressInfo;
}
