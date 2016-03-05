<?php
require_once "PDOConnection.php";
//TODO warehouse
function add_cart_header($cartHeader)
{
    $dbh = new PDOConnection();

    $user_id = $cartHeader['user_id'];
    $ship_date = $cartHeader['ship_date'];
    $type = $cartHeader['type'];
    $shipping_type = $cartHeader['shipping_type'];
    $comments = isset($cartHeader['comments']) ? $cartHeader['comments'] : '';
    $shipping_comments = isset($cartHeader['shipping_comments']) ? $cartHeader['shipping_comments'] : '';
    $warehouse = isset($cartHeader['warehouse']) ? $cartHeader['warehouse'] : ''; //TODO this

    if(check_cart_exists($dbh,$user_id))
    {
        throw new Exception("User cart already exists");
    }

    $query = "INSERT INTO cart_header(user_id, ship_date, type, shipping_type, comments, shipping_comments) ";
    $query .= "VALUES(:user_id, :ship_date, :type, :shipping_type, :comments, :shipping_comments)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id);
    $sth->bindParam(':ship_date', $ship_date);
    $sth->bindParam(':type', $type);
    $sth->bindParam(':shipping_type', $shipping_type);
    $sth->bindParam(':comments', $comments);
    $sth->bindParam(':shipping_comments', $shipping_comments);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return true;
}

function add_cart_detail($cartDetail)
{
    $dbh = new PDOConnection();

    $user_id = $cartDetail['user_id'];
    $product_id = $cartDetail['product_id'];
    $price = $cartDetail['price'];
    $quantity = $cartDetail['quantity'];
    $unit_id = $cartDetail['unit_id'];

    if(!check_cart_exists($dbh,$user_id))
    {
        throw new Exception("Cannot find cart for user");
    }

    $query = "INSERT INTO cart_detail(user_id, product_id, price, quantity, unit_id) ";
    $query .= "VALUES(:user_id, :product_id, :price, :quantity, :unit_id)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->bindParam(':product_id',$product_id);
    $sth->bindParam(':price',$price);
    $sth->bindParam(':quantity',$quantity);
    $sth->bindParam(':unit_id',$unit_id);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return true;
}
function check_cart_exists($dbh,$user_id)
{
    $query = "SELECT user_id FROM cart_header WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->execute();
    return ($sth->rowCount() > 0);
}
