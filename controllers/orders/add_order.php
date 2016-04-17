<?php
/*
INPUTS:
user_id
order_date
delivery_date
delivery_method?
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
    $order_date = isset($orderArray['order_date']) ? $orderArray['order_date'] : date("Y-m-d");
    $delivery_date = $orderArray['delivery_date'];

    //default values
    $delivery_method = isset($orderArray['delivery_method']) ? $orderArray['delivery_method'] : 0; //pickup/delivery
    $status = isset($orderArray['status']) ? $orderArray['status'] : "open";
    $comments = isset($orderArray['comments']) ? $orderArray['comments']: '';
    $shipping_comments = isset($orderArray['shipping_comments']) ? $orderArray['shipping_comments'] : '';

    $query = "INSERT INTO orders(user_id, order_date,delivery_date,delivery_method,status,comments,shipping_comments, total_price) 
        VALUES(:user_id, :order_date, :delivery_date, :delivery_method, :status, :comments, :shipping_comments, :total_price)";

    $sth = $dbh->prepare($query);
    $orderArr = array(':user_id' => $user_id, 
        ':order_date' => $order_date, 
        ':delivery_date' => $delivery_date, 
        ':delivery_method' => $delivery_method, 
        ':status'=> $status, 
        ':comments' => $comments, 
        ':shipping_comments' => $shipping_comments,
        ':total_price' => $total_price);
    if(!$sth->execute($orderArr))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $order_id = $dbh->lastInsertId();
    if(isset($orderArray['lines']))
    {
        add_order_detail($dbh,$order_id,$orderArray['lines']);
    }
    return array('id' => $order_id);
}
function add_order_detail($dbh, $order_id, $detailArray)
{
    //initialize parameters for binding 
    $product_id = 0;
    $unit_id = 0;
    $price = 0;
    $quantity = 0;
    $line_id = 0;

    $query = "INSERT INTO order_details(order_id,line_id,product_id,price,quantity,unit_id) 
        VALUES(:order_id,:line_id, :product_id, :price, :quantity, :unit_id)";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':order_id',$order_id, PDO::PARAM_INT);
    $sth->bindParam(':line_id',$line_id, PDO::PARAM_INT);
    $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
    $sth->bindParam(':unit_id',$unit_id, PDO::PARAM_INT);
    $sth->bindParam(':price',$price, PDO::PARAM_INT);
    $sth->bindParam(':quantity',$quantity, PDO::PARAM_INT);

    foreach($detailArray as $detail)
    {
        $product_info = GetProductInfoByCode($dbh,$detail);
        $detail['product_id'] = isset($detail['product_id']) ? $detail['product_id'] : $product_info['id'];
        $detail['price'] = isset($detail['price']) ? $detail['price'] : $product_info['price'];

        if(!(isset($detail['product_id']) 
            && isset($detail['price']) 
            && isset($detail['quantity'])
            && isset($detail['unit_id'])))
        {
            //something is missing
            throw new Exception("ERROR in add_order_detail: product_id, price, quantity, unit_id required. \n");
        }

        $product_id = $detail['product_id'];
        $unit_id = $detail['unit_id'];
        $price = $detail['price'];
        $quantity = $detail['quantity'];

        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        ++$line_id;
    }
}

function GetProductInfoByCode($dbh, $detail)
{
        $product_query = "SELECT id,price FROM products WHERE code = :prod_code";
        $product_sth = $dbh->prepare($product_query);
        $product_sth->bindParam(':prod_code', $detail['product_code']);
        $product_sth->execute() or die($sth->errorInfo()[2]);
        $row = $product_sth->fetch(PDO::FETCH_ASSOC);
        return $row;
}
