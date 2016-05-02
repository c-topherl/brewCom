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
function add_order($orderInfo)
{
    $dbh = new PDOConnection();

    //default values
    $orderInfo['order_date'] = isset($orderInfo['order_date']) ? $orderInfo['order_date'] : date("Y-m-d");
    $orderInfo['delivery_method'] = isset($orderInfo['delivery_method']) ? $orderInfo['delivery_method'] : 0; //pickup/delivery
    $orderInfo['status'] = isset($orderInfo['status']) ? $orderInfo['status'] : "open";
    $orderInfo['comments'] = isset($orderInfo['comments']) ? $orderInfo['comments']: '';
    $orderInfo['shipping_comments'] = isset($orderInfo['shipping_comments']) ? $orderInfo['shipping_comments'] : '';
    $orderInfo['total_price'] = 0;
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
    if(isset($orderInfo['lines']))
    {
        add_order_detail($dbh,$order_id,$orderInfo['lines']);
    }

    //return all order data to display on the order confirmation page
    $orderConf = $orderInfo;
    $orderConf['order_id'] = $order_id;
    /*
        TODO
        note: need to get text
		"delivery_method": "Standard delivery",

        note: format
		"delivery_date": "06/12/2016",

        a lot to build out here. see issue #56
		"ship_to_name": "Customer name",
		"ship_to_addr1": "123 Main Street",
		"ship_to_addr2": "Suite 101",
		"ship_to_city": "Nowheresville",
		"ship_to_state": "Maryland",
		"ship_to_zip": 58293,
		"bill_to_name": "Customer name",
		"bill_to_addr1": "123 Main Street",
		"bill_to_addr2": "Suite 101",
		"bill_to_city": "Nowheresville",
		"bill_to_state": "Maryland",
		"bill_to_zip": 58293,
        */
    return $orderConf;
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
