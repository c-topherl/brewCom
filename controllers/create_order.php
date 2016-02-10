<?php
include "DBConnection.php";
function create_order($orderArray)
{
    $dbConn = new DBConnection();
    //pass in all order details in an array
    $user_id = $orderArray['user_id'];
    $order_date = isset($orderArray['order_date']) ? $orderArray['order_date'] : date("Y m d");
    $ship_date = $orderArray['ship_date'];
    $type = isset($orderArray['type']) ? $orderArray['type'] : 'pickup'; //pickup/delivery
    $status = isset($orderArray['status']) ? $orderArray['status'] : "open";
    $comments = isset($orderArray['comments']) ? $orderArray['comments']: '';
    $shipping_commenta = isset($orderArray['shipping_comments']) ? $orderArray['shipping_comments'] : '';

    $sql = "INSERT INTO orders(user_id, order_date,ship_date,type,status,comments,shipping_comments) ";
    $sql .= "VALUES($user_id, '$order_date','$ship_date','$type','$status','$comments','$shipping_comments')";
    $dbConn->db_connect();
    if(!($result = $dbConn->db_query($sql)))
    {
        $dbConn->db_error();
        return false;
    }

    if(isset($orderArray['detail']))
    {
        create_order_detail($dbConn,mysqli_insert_id($dbConn),$orderArray['detail']);
    }
    return true;
}
function create_order_detail($dbConn, $order_id, $detailArray)
{
    foreach($detailArray as $detail)
    {
        $product_id = $detail['product_id'];
        $price = $detail['price'];
        $quantity = $detail['quantity'];
        $unit_id = $detail['unit_id'];
        $sql = "INSERT INTO order_details(order_id,product_id,price,quantity,unit_id) ";
        $sql .= "VALUES($order_id,$product_id,$price,$quantity,$unit_id)";
        if(!($result = $dbConn->db_query($sql)))
        {
            $dbConn->db_error();
            return false;
        }
    }
}
