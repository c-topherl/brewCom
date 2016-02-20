<?php
require_once("PDOConnection.php");
function get_orders($orderInfo = NULL, &$error = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT order_id,user_id,total_price,order_date,ship_date,type,shipping_type,status,comments,shipping_comments,u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ";
    $optionalParams = array();;
    if(isset($orderInfo['order_id']))
    {
        $order_id = $orderInfo['order_id'];
        $optionalParams[] = "o.order_id = :order_id ";
    }
    if(isset($orderInfo['status']))
    {
        $status = $orderInfo['status'];
        $optionalParams[] = "o.status = :status ";
    }
    if(isset($orderInfo['email']))
    {
        $email  = $orderInfo['email'];
        $optionalParams[] = "u.email = :email ";
    }
    if(count($optionalParams) > 0)
    {
        $query .= "WHERE ";
        foreach($optionalParams as $param)
        {
            $query .= $param." ";
        }
    }
    $sth = $dbh->prepare($query);
    if(isset($order_id))
    {
        $sth->bindParam(':order_id',$order_id);
    }
    if(isset($status))
    {
        $sth->bindParam(':status',$status);
    }
    if(isset($email))
    {
        $sth->bindParam(':email',$email);
    }

    $orderArray = array();
    $sth->execute();
    $result = $sth->fetchAll();
    foreach($result as $row)
    {
        $orderArray[$row['order_id']] = $row;
        $orderArray[$row['order_id']]['detail'] = get_order_details($dbh,$row['order_id']);
        $idx++;
    }
    return $orderArray;
}
function get_order_detail($order_id)
{
    return get_order_details(new PDOConnection(),$order_id);
}
function get_order_details($dbh, $order_id)
{
    $detailArray = array();
    $query = "SELECT od.price,quantity,unit_id,p.code product_code FROM order_details od ";
    $query .= "LEFT JOIN products p ON od.product_id = p.id ";
    $query .= "WHERE order_id = :order_id ";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':order_id',$order_id);
    $sth->execute();
    foreach($sth->fetchAll() as $row)
    {
        $detailArray[$row['id']] = $row;
    }
    return $detailArray;
}
