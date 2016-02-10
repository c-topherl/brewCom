<?php
require_once("DBConnection.php");
function get_orders()
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
    $sql = "SELECT * from orders";
    $orderArray = array();
    if($result = $dbConn->db_query($sql))
    {
        $idx = 0;
        while($row = mysqli_fetch_assoc($result))
        {
            $orderArray[$idx] = $row;
            $orderArray[$idx]['detail'] = get_order_details($dbConn,$row['order_id']);
            $idx++;
        }
    }
    else
    {
        echo $dbConn->db_error();
        return false;
    }
    return $orderArray;
}
function get_order_details($dbConn, $order_id)
{
    $detailArray = array();
    $sql = "SELECT * FROM order_details WHERE order_id = $order_id";
    if($result = $dbConn->db_query($sql))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $detailArray[] = $row;
        }
    }
    return $detailArray;
}
