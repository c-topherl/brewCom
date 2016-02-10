<?php
include "DBConnection.php";
function create_order($orderArray)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
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
    if(!($result = $dbConn->db_query($sql)))
    {
        $dbConn->db_error();
        return false;
    }
    if(isset($orderArray['detail']))
    {
        create_order_detail($dbConn,mysqli_insert_id($dbConn->get_con()),$orderArray['detail']);
    }
    return true;
}
function create_order_detail($dbConn, $order_id, $detailArray)
{
    foreach($detailArray as $detail)
    {
        $sql = "SELECT id,price FROM products WHERE code = '{$detail['product_code']}'";
        if($result = $dbConn->db_query($sql))
        {
            if($row = mysqli_fetch_assoc($result))
            {
                $detail['product_id'] = $row['id'];
                $detail['price'] = $row['price'];
            }
        }
        else
        {
            continue;
        }
        $product_id = $detail['product_id'];
        $price = $detail['price'];
        $quantity = 0;//$detail['quantity'];
        $unit_id = 0;//$detail['unit_id'];
        $sql = "INSERT INTO order_details(order_id,product_id,price,quantity,unit_id) ";
        $sql .= "VALUES($order_id,$product_id,$price,$quantity,$unit_id)";
        if(!($result = $dbConn->db_query($sql)))
        {
            $dbConn->db_error();
            return false;
        }
    }
}
