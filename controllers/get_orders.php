<?php
/*
   INPUTS:
   order_id
   status
   user_id
 */
require_once("PDOConnection.php");
function get_orders($filters = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT order_id, user_id, total_price, order_date,ship_date,type,shipping_type,status,comments,shipping_comments,u.username 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id ";
    $optionalParams = array();;
    if(isset($order_id))
    {
        $optionalParams[] = "o.order_id = :order_id ";
        $order_id = $filters['order_id'];
    }
    if(isset($status))
    {
        $optionalParams[] = "o.status = :status ";
        $status = $filters['status'];
    }
    if(isset($user_id))
    {
        $optionalParams[] = "u.id = :user_id ";
        $user_id  = $filters['user_id'];
    }
    if(isset($start_date))
    {
        $optionalParams[] = "o.ship_date >= :start_ship_date ";
        $start_date = $filters['start_date'];
    }
    if(isset($end_date))
    {
        $optionalParams[] = "o.ship_date <= :end_ship_date ";
        $end_date = $filters['end_date'];
    }
    if(count($optionalParams) > 0)
    {
        $query .= "WHERE ";
        $query .= implode("AND ",$optionalParams);
    }
    $sth = $dbh->prepare($query);
    if(isset($order_id))
        $sth->bindParam(':order_id',$order_id);
    if(isset($status))
        $sth->bindParam(':status',$status);
    if(isset($user_id))
        $sth->bindParam(':user_id',$user_id);
    if(isset($start_date))
        $sth->bindParam(':start_ship_date',$start_date);
    if(isset($end_date))
        $sth->bindParam(':end_ship_date',$end_date);

    $orderArray = array();
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $orderArray[$row['order_id']] = $row;
        $idx++;
    }
    return array('orders' => $orderArray);
}
function get_order_detail($values)
{
    return array('order_details' => get_order_details(new PDOConnection(),$values['order_id']));
}
function get_order_details($dbh, $order_id)
{
    $detailArray = array();
    $query = "SELECT od.id, od.price, quantity, 
            p.id product_id, p.code product_code, p.description product_description, 
            unit_id, u.code unit_code, u.description unit_description 
        FROM order_details od 
        LEFT JOIN products p ON od.product_id = p.id 
        LEFT JOIN units u ON unit_id = u.id 
        WHERE order_id = :order_id ";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':order_id',$order_id);
    $sth->execute();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $detailArray[$row['id']] = $row;
    }
    return $detailArray;
}
