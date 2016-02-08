<?php

function create_order()
{
    $dbconn = new DBConnection();
    //pass in all order details in an array
    $user_id = $_POST['user_id'];
    $order_date = isset($_POST['order_date']) ? $_POST['order_date'] : date("Y m d");
    $ship_date = $_POST['ship_date'];
    $type = isset($_POST['type']) ? $_POST['type'] : 'pickup'; //pickup/delivery
    $status = isset($_POST['status']) ? $_POST['status'] : "open";
    $comments = isset($_POST['comments']) ? $_POST['comments']: '';
    $shipping_commenta = isset($_POST['shipping_comments']) ? $_POST['shipping_comments'] : '';

    $sql = "INSERT INTO orders(user_id, order_date,ship_date,type,status,comments,shipping_comments) ";
    $sql .= "VALUES($user_id, '$order_date','$ship_date','$type','$status','$comments','$shipping_comments')";
    $dbConn->db_connect();
    if(!($result = $dbConn->db_query($sql)))
    {
        $dbConn->db_error();
        return false;
    }

    if(isset($_POST['detail']))
    {
        create_order_detail($dbConn,mysqli_insert_id($dbConn),$_POST['detail']);
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
