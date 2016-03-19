<?php
require_once("PDOConnection.php");
include_once('get_cart.php');
include_once('add_order.php');
function submit_order($values)
{
    $user_id = $values['user_id'];
    $email = isset($values['email']) ? $values['email'] : '';
    $comments = isset($values['comments']) ? $values['comments'] : '';
    $dbh = new PDOConnection();
    $cart = get_cart(array('user_id'=>$user_id))['cart'];
    $details = get_cart_details($dbh, $user_id);
    print_r($cart);
    print_r($details);
    $order = $cart;
    $order['detail'] = $details;
}
?>
