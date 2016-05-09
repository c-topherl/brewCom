<?php
require_once "PDOConnection.php";
include_once "orders/common_cart_functions.inc";

function delete_cart($info)
{
    if(!isset($info['user_id']))
    {
        throw new Exception("user_id required.");
    }
    $dbh = new PDOConnection();
    if(!check_cart_exists($dbh,$info['user_id']))
    {
        return "No cart found for user";
    }
    $query = "DELETE FROM cart_headers WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $info['user_id'], PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception("delete_cart error: ".$sth->errorInfo()[2]);
    }

    $query = "DELETE FROM cart_details WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $info['user_id'], PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception("delete_cart details error: ".$sth->errorInfo()[2]);
    }
    return TRUE;
}

function delete_cart_detail($cartDetail)
{
    if(!(isset($cartDetail['user_id']) && isset($cartDetail['line_id'])))
    {
        throw new Exception('Must provide user_id and line_id');
    }
    $line_id = $cartDetail['line_id'];
    $user_id = $cartDetail['user_id'];
    $dbh = new PDOConnection();

    if(!check_cart_exists($dbh,$user_id))
    {
        throw new Exception("Cannot find cart for user");
    }

    //remove all cart_details for current user
    $query = "DELETE FROM cart_details WHERE user_id = :user_id AND line_id = :line_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->bindParam(':line_id', $line_id, PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    //update line id for the rest of the cart
    $query = "UPDATE cart_details SET line_id = (line_id - 1) WHERE user_id = :user_id AND line_id > :line_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->bindParam(':line_id', $line_id, PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    return true;
}
