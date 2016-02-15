<?php
include "PDOConnection.php";
function add_order($orderArray)
{
    $dbh = new PDOConnection();
    //pass in all order details in an array
    $user_id = $orderArray['user_id'];
    $order_date = isset($orderArray['order_date']) ? $orderArray['order_date'] : date("Y m d");
    $ship_date = $orderArray['ship_date'];
    $type = isset($orderArray['type']) ? $orderArray['type'] : 'pickup'; //pickup/delivery
    $status = isset($orderArray['status']) ? $orderArray['status'] : "open";
    $comments = isset($orderArray['comments']) ? $orderArray['comments']: '';
    $shipping_commenta = isset($orderArray['shipping_comments']) ? $orderArray['shipping_comments'] : '';
    $query = "INSERT INTO orders(user_id, order_date,ship_date,type,status,comments,shipping_comments) ";
    $query .= "VALUES($user_id, '$order_date','$ship_date','$type','$status','$comments','$shipping_comments')";
    $dbh->query($query);

    if(isset($orderArray['detail']))
    {
        add_order_detail($dbh,$dbh->lastInsertId(),$orderArray['detail'])
    }
    return true;
}
function add_order_detail($dbh, $order_id, $detailArray)
{
    foreach($detailArray as $detail)
    {
        $query = "SELECT id,price FROM products WHERE code = '{$detail['product_code']}'";
        foreach($dbh->query($query) as $row)
        {
            $detail['product_id'] = $row['id'];
            $detail['price'] = isset($detail['price']) ? $detail['price'] : $row['price'];
        }
        if(!(isset($detail['product_id']) && (isset($detail['price']) && (isset($detail['quantity'])&& (isset($detail['unit_id']))
        {
            //something is missing
            return false;
        }
        $product_id = $detail['product_id'];
        $price = $detail['price'];
        $quantity = $detail['quantity'];
        $unit_id = $detail['unit_id'];
        $query = "INSERT INTO order_details(order_id,product_id,price,quantity,unit_id) ";
        $query .= "VALUES($order_id,$product_id,$price,$quantity,$unit_id)";
        $dbh->query($query)
    }
}
