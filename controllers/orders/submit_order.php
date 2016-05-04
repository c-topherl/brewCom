<?php
/*
    submit an order given the user id and optional email and comments

    email will be retrieved from users table if not filled in
*/
require_once("PDOConnection.php");
include_once('mail.inc');
include_once('token.inc');
include_once('customers/get_users.php');
include_once('orders/get_cart.php');
include_once('orders/add_order.php');

function submit_order($values)
{
    if(!(isset($values['user_id']) && isset($values['token'])))
    {
        throw new Exception('user_id and token required');
    }
    if(FALSE === VerifyToken($values['token'], $values['user_id'], NULL))
    {
        throw new Exception('Your session has expired.  Please log in again.');
    }
    //set up all data to be passed to add_order()
    $userInfo = get_users(array('id' => $user_id));
    $email = isset($values['email']) ? $values['email'] : $userInfo['email'];

    $dbh = new PDOConnection();
    $order = get_cart_information($dbh, $values);

    $orderInfo = add_order($order);
    order_confirmation_email(array_merge($orderInfo,array('email' => $email)));

    //delete cart
    delete_cart_by_user_id($dbh, $values['user_id']);
    return $orderInfo;
}
function get_cart_information($dbh, $values)
{
    $user_id = $values['user_id'];

    $cart = get_cart(array('user_id'=>$user_id));
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
