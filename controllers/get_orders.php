<?php
require_once("DBConnection.php");
function get_orders()
{
    $dbConn = new DBConnection();
    $sql = "SELECT * from orders";
    $orderArray = array();
    $dbConn->db_connect();
    if($result = $dbConn->db_query($sql))
    {
        $idx = 0;
        while($row = mysqli_fetch_assoc($result))
        {
            $orderArray[$idx] = $row;
            $orderArray[$idx]['detail'] = get_order_details($row['order_id']);
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
function get_order_details($order_id)
{
    $sql = "SELECT * FROM order_details WHERE order_id = $order_id";
}
