<?php
require_once("PDOConnection.php");
TODO: THIS WHOLE THING
//This function is to get the shopping cart given a number of parameters
function get_cart($orderInfo = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT user_id,total_price,order_date,ship_date,type,shipping_type,status,comments,shipping_comments,u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ";
    $optionalParams = '';
    if(isset($orderInfo['email']))
    {
        $email  = $orderInfo['email'];
        $query2 = "SELECT id,username,email FROM users WHERE email = '$email'";
        $sth2 = $dbh->prepare($query2);
        $sth2->execute();
        $row = $sth2->fetch();
        if(!isset($row['email']))
        {
            $error = "Customer email does not exist";
            return NULL;
        }
        $optionalParams .= "u.email = '{$row['email']}' ";
    }
    if($optionalParams !== '')
    {
        $query .= "WHERE ".$optionalParams;
    }
    $orderArray = array();
    foreach($dbh->query($query) as $row)
    {
        $orderArray[$row['order_id']] = $row;
        $orderArray[$row['order_id']]['detail'] = get_order_details($dbh,$row['order_id']);
        $idx++;
    }
    return $orderArray;
}
function get_order_details($dbh, $order_id)
{
    $detailArray = array();
    $query = "SELECT od.price,quantity,unit_id,p.code product_code FROM order_details od ";
    $query .= "LEFT JOIN products p ON od.product_id = p.id ";
    $query .= "WHERE order_id = $order_id ";
    foreach($dbh->query($query) as $row)
    {
        $detailArray[$row['id']] = $row;
    }
    return $detailArray;
}
