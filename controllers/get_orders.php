<?php
require_once("PDOConnection.php");
function get_orders()
{
    $dbh = new PDOConnection();
    $query = "SELECT order_id,user_id,total_price,order_date,ship_date,type,shipping_type,status,comments,shipping_comments,u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id";
    $orderArray = array();
    $idx = 0;
    foreach($dbh->query($query) as $row)
    {
        $orderArray[$idx] = $row;
        $orderArray[$idx]['detail'] = get_order_details($dbh,$row['order_id']);
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
        $detailArray[] = $row;
    }
    return $detailArray;
}
