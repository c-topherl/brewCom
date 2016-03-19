<?php
/*
INPUTS:
user_id
order_date
ship_date
type?
status?
comments?
shipping_comments?
*/
require_once "PDOConnection.php";
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
    $query = "INSERT INTO orders(user_id, order_date,ship_date,type,status,comments,shipping_comments) 
        VALUES(:user_id, :order_date, :ship_date, :type, :status, :comments, :shipping_comments)";
    $sth = $dbh->prepare($query);
    $orderArr = array(':user_id' => $user_id, ':order_date' => $order_date, 
        ':ship_date' => $ship_date, ':type' => $type, ':status'=> $status, 
        ':comments' => $comments, ':shipping_comments' => $shipping_comments);
    if(!$sth->execute($orderArr))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $order_id = $dbh->lastInsertId();
    if(isset($orderArray['detail']))
    {
        add_order_detail($dbh,$order_id,$orderArray['detail']);
    }
    return array('id' => $order_id);
}
function add_order_detail($dbh, $order_id, $detailArray)
{
    foreach($detailArray as $detail)
    {
        $query = "SELECT id,price FROM products WHERE code = :prod_code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':prod_code', $detail['product_code']);
        $sth->execute();
        $row = $sth->fetch();
        $detail['product_id'] = $row['id'];
        $detail['price'] = isset($detail['price']) ? $detail['price'] : $row['price'];
        if(!(isset($detail['product_id']) && isset($detail['price']) && isset($detail['quantity'])&& isset($detail['unit_id'])))
        {
            //something is missing
            throw new Exception("Product_id, price, quantity, unit_id required");
        }
        $product_id = $detail['product_id'];
        $price = $detail['price'];
        $quantity = $detail['quantity'];
        $unit_id = $detail['unit_id'];
        $query = "INSERT INTO order_details(order_id,product_id,price,quantity,unit_id) 
            VALUES(:order_id, :product_id, :price, :quantity, :unit_id)";
        $sth = $dbh->prepare($query);
        $details = array(':order_id' => $order_id, ':product_id' => $product_id, 
            ':price' => $price, ':quantity' => $quantity, ':unit_id' => $unit_id);
        if(!$sth->execute($details))
        {
            throw new Exception($sth->errorInfo()[2]);
        }
    }
}
