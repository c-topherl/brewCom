<?php
include_once 'PDOConnection.php';
include_once 'orders/common_cart_functions.inc'; //check_cart_exists()

function update_cart_header($cartInfo)
{
    return 'not implemented';

    if(!isset($cartInfo['user_id']))
    {
        throw new Exception("ERROR: user_id required");
    }

    $dbh = new PDOConnection();
    if(!check_cart_exists($dbh, $cartInfo['user_id']))
    {
        throw new Exception("Could not find cart for user: ".$cartInfo['user_id']);
    }

    //update cart header information
    $query = "UPDATE cart_header SET ";

    $optionalQuery = '';
    if(isset($cartInfo['delivery_date']))
    {
        $optionalQuery .= 'delivery_date = :delivery_date ';
    }
    if(isset($cartInfo['delivery_method']))
    {
        $optionalQuery .= 'delivery_method = :delivery_method ';
    }
    if(isset($cartInfo['comments']))
    {
        $optionalQuery .= 'comments = :comments ';
    }
    if(isset($cartInfo['shipping_comments']))
    {
        $optionalQuery .= 'shipping_comments = :shipping_comments ';
    }
    if($optionalQuery === '')
    {
        throw new Exception('Nothing to update');
    }
    $query .= $optionalQuery . " WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $cartInfo['user_id'], PDO::PARAM_INT);
    if(isset($cartInfo['delivery_date']))
    {
        $sth->bindParam(':delivery_date', $cartInfo['delivery_date']);
    }
    if(isset($cartInfo['delivery_method']))
    {
        $sth->bindParam(':delivery_method', $cartInfo['delivery_method']);
    }
    if(isset($cartInfo['comments']))
    {
        $sth->bindParam(':comments', $cartInfo['comments']);
    }
    if(isset($cartInfo['shipping_comments']))
    {
        $sth->bindParam(':shipping_comments', $cartInfo['shipping_comments']);
    }
    if(!$sth->exeicute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }


    if(isset($cartInfo['lines']))
    {
        //update cart lines
    }

}
