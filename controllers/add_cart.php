<?php
include "DBConnection.php";
function add_cart_header($cartHeader)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();

    $user_id = $cartHeader['user_id'];
    $ship_date = $cartHeader['ship_date'];
    $type = $cartHeader['type'];
    $shipping_type = $cartHeader['shipping_type'];
    $comments = $cartHeader['comments'];
    $shipping_comments = $cartHeader['shipping_comments'];

    if(check_cart_exists($dbConn,$user_id))
    {
        return false; //may only have 1 cart
    }

    $sql = "INSERT INTO orders(user_id, ship_date, type, shipping_type, comments, shipping_comments) ";
    $sql .= "VALUES($user_id, $ship_date, $type, $shipping_type, '$comments', '$shipping_comments')";
    if(!($result = $dbConn->db_query($sql)))
    {
        $dbConn->db_error();
        return false;
    }
    return true;
}

function add_cart_detail($cartDetail)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();

    $user_id = $cartDetail['user_id'];
    $product_id = $cartDetail['product_id'];
    $price = $cartDetail['price'];
    $quantity = $cartDetail['quantity'];
    $unit_id = $cartDetail['unit_id'];

    if(!check_cart_exists($dbConn,$user_id))
    {
        return false; //must have cart
    }

    $sql = "INSERT INTO orders(user_id, product_id, price, quantity, unit_id) ";
    $sql .= "VALUES($user_id, $product_id, $price, $quantity, $unit_id)";
    if(!($result = $dbConn->db_query($sql)))
    {
        $dbConn->db_error();
        return false;
    }
    return true;
}
function check_cart_exists($dbConn,$user_id)
{
    $sql = "SELECT user_id FROM cart_header WHERE user_id = $user_id";
    $result = $dbConn->db_query($sql);
    if($result && (mysqli_num_rows($result) > 0))
    {
        return true;
    }
    return false;
}
