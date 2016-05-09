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
require_once "orders/get_orders.php";
function add_order($orderInfo)
{
    $orderInfo = GetDefaultOrderInfo($orderInfo);

    $dbh = new PDOConnection();
    $order_id = AddOrderFromInfo($dbh, $orderInfo);

    if(isset($orderInfo['lines']))
    {
        add_order_detail($dbh, $order_id, $orderInfo['lines']);
    }

    //return all order data to display on the order confirmation page
    $orderConf = get_order_detail(array('details' => 1, 'order_id' => $order_id));
    return $orderConf;
}

function GetDefaultOrderInfo($orderInfo)
{

    //default values
    $orderInfo['order_date'] = isset($orderInfo['order_date']) ? $orderInfo['order_date'] : date("Y-m-d");
    $orderInfo['delivery_method'] = isset($orderInfo['delivery_method']) ? $orderInfo['delivery_method'] : 1; //pickup/delivery
    $orderInfo['status'] = isset($orderInfo['status']) ? $orderInfo['status'] : "open";
    $orderInfo['comments'] = isset($orderInfo['comments']) ? $orderInfo['comments']: '';
    $orderInfo['shipping_comments'] = isset($orderInfo['shipping_comments']) ? $orderInfo['shipping_comments'] : '';
    if(isset($orderInfo['total_price']))
    {
        //if we were given a total price override
        $orderInfo['total_price'] = $orderInfo['total_price'];
    }
    elseif(isset($orderInfo['lines']))
    {
        //Get the total price from the lines
        $orderInfo['total_price'] =  array_sum(array_map(function($row){
                    return $row['quantity'] * $row['price'];
                    }, $orderInfo['lines']));
    }
    else
    {
        $orderInfo['total_price'] = 0;
    }
    return $orderInfo;
}

function AddOrderFromInfo($dbh, $orderInfo)
{
    $query = "INSERT INTO orders(user_id, order_date,delivery_date,delivery_method,status,comments,shipping_comments, total_price) 
        VALUES(:user_id, :order_date, :delivery_date, :delivery_method, :status, :comments, :shipping_comments, :total_price)";

    $sth = $dbh->prepare($query);
    $orderExecArr = array(':user_id' => $orderInfo['user_id'], 
            ':order_date' => $orderInfo['order_date'], 
            ':delivery_date' => $orderInfo['delivery_date'], 
            ':delivery_method' => $orderInfo['delivery_method'], 
            ':status'=> $orderInfo['status'], 
            ':comments' => $orderInfo['comments'], 
            ':shipping_comments' => $orderInfo['shipping_comments'],
            ':total_price' => $orderInfo['total_price']);
    if(!$sth->execute($orderExecArr))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $order_id = $dbh->lastInsertId();
    AddOrderAddress($dbh, $order_id, $orderInfo['shipping_address_id']);
    AddOrderAddress($dbh, $order_id, $orderInfo['billing_address_id']);
    return $order_id;
}
function AddOrderAddress($dbh, $order_id, $address_id)
{
    //TODO billing and shipping addresses
    $query = "INSERT INTO order_addresses(order_id, type, name, address1, address2, city, state, zipcode) SELECT :order_id, type, name, address1, address2, city, state, zipcode FROM addresses WHERE id = :address_id";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $sth->bindParam(':address_id', $address_id, PDO::PARAM_INT);
    if(!$sth->execute($orderExecArr))
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    return TRUE;
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
