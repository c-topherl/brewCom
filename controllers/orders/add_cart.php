<?php
require_once "PDOConnection.php";
require_once "common.inc";
include_once "orders/common_cart_functions.inc";
include_once "customers/get_addresses.php";
//TODO: warehouse
function add_cart_header($cartHeader)
{
    $dbh = new PDOConnection();

    if(!(isset($cartHeader['user_id']) && $cartHeader['delivery_date'] && $cartHeader['delivery_method']))
    {
        throw new Exception("Must provide user_id, delivery_date, and delivery_method");
    }
    $user_id = $cartHeader['user_id'];

    if(check_cart_exists($dbh,$user_id))
    {
        throw new Exception("User cart already exists");
    }

    $delivery_date = $cartHeader['delivery_date'];
    $delivery_method = $cartHeader['delivery_method'];

    //optional parameters
    $shipping_type = isset($cartHeader['shipping_type']) ? $cartHeader['shipping_type'] : '';
    $comments = isset($cartHeader['comments']) ? $cartHeader['comments'] : '';
    $shipping_comments = isset($cartHeader['shipping_comments']) ? $cartHeader['shipping_comments'] : '';
    $warehouse = isset($cartHeader['warehouse']) ? $cartHeader['warehouse'] : ''; //TODO: this
    $address_id = isset($cartHeader['address_id']) ? $cartHeader['address_id'] : NULL; //TODO: this

    //get address id from user/customers
    if(empty($address_id))
    {
        $addresses = get_addresses(array('user_id' => $user_id, 'customer_id' => -1, 'type' => ADDRESS_TYPE_SHIP));
        $address_id = isset($addresses[0]['address_id']) ? $addresses[0]['address_id'] : -1; //no address
    }

    $query = "INSERT INTO cart_headers(user_id, delivery_date, delivery_method, shipping_type, address_id, comments, shipping_comments) ";
    $query .= "VALUES(:user_id, :delivery_date, :delivery_method, :shipping_type, :address_id, :comments, :shipping_comments)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id);
    $sth->bindParam(':delivery_date', $delivery_date);
    $sth->bindParam(':delivery_method', $delivery_method);
    $sth->bindParam(':shipping_type', $shipping_type);
    $sth->bindParam(':comments', $comments);
    $sth->bindParam(':shipping_comments', $shipping_comments);
    $sth->bindParam(':address_id', $address_id);

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
    if(empty($cartDetail['lines']))
    {
        throw new Exception("'lines' is empty");
    }
    $details = $cartDetail['lines'];
    $user_id = $cartDetail['user_id'];
    $dbh = new PDOConnection();

    if(!check_cart_exists($dbh,$user_id))
    {
        throw new Exception("Cannot find cart for user");
    }

    //remove all cart_details for current user
    $query = "DELETE FROM cart_details WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    //set up parameters to bind
    $product_id = -1;
    $unit_id = -1;
    $price = -1;
    $quantity = -1;
    $line_id = 0;

    $query = "INSERT INTO cart_details(user_id, product_id, unit_id, price, quantity, line_id)
        VALUES(:user_id, :product_id, :unit_id, :price, :quantity, :line_id)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id',$user_id);
    $sth->bindParam(':product_id',$product_id);
    $sth->bindParam(':price',$price);
    $sth->bindParam(':quantity',$quantity);
    $sth->bindParam(':unit_id',$unit_id);
    $sth->bindParam(':line_id',$line_id);

    foreach($details as $detail)
    {
        $detail = (array)$detail;
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
        $line_id = isset($detail['line_id']) ? $detail['line_id'] : $line_id;

        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        ++$line_id;
    }

    return true;
}
