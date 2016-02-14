<?php
include "PDOConnection.php";
function add_cart_header($cartHeader)
{
    $dbj = new PDOConnection();

    $user_id = $cartHeader['user_id'];
    $ship_date = $cartHeader['ship_date'];
    $type = $cartHeader['type'];
    $shipping_type = $cartHeader['shipping_type'];
    $comments = $cartHeader['comments'];
    $shipping_comments = $cartHeader['shipping_comments'];

    if(check_cart_exists($dbh,$user_id))
    {
        return false; //may only have 1 cart
    }

    $query = "INSERT INTO orders(user_id, ship_date, type, shipping_type, comments, shipping_comments) ";
    $query .= "VALUES($user_id, $ship_date, $type, $shipping_type, '$comments', '$shipping_comments')";
    $dbh->query($query);
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
        return false; //must have cart
    }

    $query = "INSERT INTO orders(user_id, product_id, price, quantity, unit_id) ";
    $query .= "VALUES($user_id, $product_id, $price, $quantity, $unit_id)";
    $dbh->query($query);
    return true;
}
function check_cart_exists($dbh,$user_id)
{
    $query = "SELECT user_id FROM cart_header WHERE user_id = $user_id";
    foreach($dbh->query($query) as $row)
    {
        return true;
    }
    return false;
}
