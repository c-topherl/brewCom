<?php
/*
    submit an order given the user id and optional email and comments

    email will be retrieved from users table if not filled in
*/
require_once("PDOConnection.php");
include_once('orders/get_cart.php');
include_once('orders/add_order.php');
include_once('customers/get_users.php');
include_once('mail.inc');

function submit_order($values)
{
    if(!isset($values['user_id']))
    {
        throw new Exception('user_id required');
    }
    
    $dbh = new PDOConnection();

    //set up all data to be passed to add_order()
    $userInfo = get_users(array('id' => $user_id));
    $email = isset($values['email']) ? $values['email'] : $userInfo['email'];

    $order = get_order_information($dbh, $values);

    $order_id = add_order($order);
    order_confirmation_email(array('email' => $email, 'order_id' => $order_id));

    //delete cart
    delete_cart_by_user_id($dbh, $values['user_id']);
    return array('order_id' => $order_id);
}
function get_order_information($dbh, $values)
{
    $user_id = $values['user_id'];

    $cart = get_cart(array('user_id'=>$user_id))['cart'];
//    $details = get_cart_details($dbh, $user_id);

    //if comments were passed in, append those to the front of the cart comments
    $cart['comments'] = isset($values['comments']) 
        ? $values['comments'] . $cart['comments'] 
            : $cart['comments'];

    //convert values to integers that can be converted to integers
    return $cart;
}
function delete_cart_by_user_id($dbh, $user_id)
{
    if(!isset($user_id))
    {
        throw new Exception('must specifiy user_id');
    }
    $query = "DELETE FROM cart_details WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    $query = "DELETE FROM cart_headers WHERE user_id = :user_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':user_id', $user_id);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return TRUE;
}
?>