<?php
require_once "PDOConnection.php";
include_once "common_cart_functions.inc";
//TODO: warehouse
function add_cart_header($cartHeader)
{
    $dbh = new PDOConnection();

    if(!(isset($cartHeader['user_id']) && $cartHeader['ship_date'] && $cartHeader['delivery_method']))
    {
        throw new Exception("Must provide user_id, ship_date, and delivery_method");
    }
    $user_id = $cartHeader['user_id'];
    $ship_date = $cartHeader['ship_date'];
    $delivery_method = $cartHeader['delivery_method'];

//optional parameters
    $shipping_type = isset($cartHeader['shipping_type']) ? $cartHeader['shipping_type'] : '';
    $comments = isset($cartHeader['comments']) ? $cartHeader['comments'] : '';
    $shipping_comments = isset($cartHeader['shipping_comments']) ? $cartHeader['shipping_comments'] : '';
    $warehouse = isset($cartHeader['warehouse']) ? $cartHeader['warehouse'] : ''; //TODO: this

    if(check_cart_exists($dbh,$user_id))
    {
        throw new Exception("User cart already exists");
    }

    $query = "INSERT INTO cart_headers(user_id, ship_date, delivery_method, shipping_type, comments, shipping_comments) ";
    $query .= "VALUES(:user_id, :ship_date, :delivery_method, :shipping_type, :comments, :shipping_comments)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id);
    $sth->bindParam(':ship_date', $ship_date);
    $sth->bindParam(':delivery_method', $delivery_method);
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
    if(!(isset($cartDetail['user_id']) && isset($cartDetail['lines'])))
    {
        throw new Exception('Must provide user_id and lines');
    }
    $details = $cartDetail['lines'];
    $user_id = $cartDetail['user_id'];
    $dbh = new PDOConnection();

    if(!check_cart_exists($dbh,$user_id))
    {
        throw new Exception("Cannot find cart for user");
    }

    //set up parameters to bind
    $product_id = -1;
    $unit_id = -1;
    $price = -1;
    $quantity = -1;

    $query = "INSERT INTO cart_details(user_id, product_id, price, quantity, unit_id)
            VALUES(:user_id, :product_id, :price, :quantity, :unit_id)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->bindParam(':product_id',$product_id);
    $sth->bindParam(':price',$price);
    $sth->bindParam(':quantity',$quantity);
    $sth->bindParam(':unit_id',$unit_id);

    file_put_contents(LOG_FILE, "adding ".print_r($details,true)."\n", FILE_APPEND);
    foreach($details as $detail)
    {
        if(!(isset($detail['product_id']) 
            && isset($detail['unit_id'])
            && isset($detail['price']) 
            && isset($detail['quantity'])))
        {
            throw new Exception("Must provide product_id, unit_id, price, quantity");
        }
        $product_id = $detail['product_id'];
        $unit_id = $detail['unit_id'];
        $price = $detail['price'];
        $quantity = $detail['quantity'];

        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        file_put_contents(LOG_FILE, "added ".print_r($detail,true)."\n", FILE_APPEND);
    }

    return true;
}
