<?php
/*
   INPUTS:
   order_id
   status
   user_id
   start_date/end_date => delivery_date
   delivery_method
 */
require_once("PDOConnection.php");
function get_orders($filters = NULL)
{
    $dbh = new PDOConnection();

    $query = "SELECT order_id, 
            total_price, 
            order_date,
            ship_date,
            delivery_date,
            delivery_method,
            user_id, 
            status
            ";

    if(isset($filters['details']))
    {
        $query .= ",comments order_comments, 
                shipping_comments,
                u.username,
            ";
                /* TODO
                bill_to_name
                bill_to_address_one
                bill_to_address_two
                ship_to_name
                ship_to_address_one
                ship_to_address_two
                */
    }
    $query .= " FROM orders o LEFT JOIN users u ON o.user_id = u.id ";

    $query .= GetOptionalParameters($filters);

    $sth = $dbh->prepare($query);
    if(isset($filters['order_id']))
        $sth->bindParam(':order_id',$filters['order_id']);
    if(isset($filters['status']))
        $sth->bindParam(':status',$filters['status']);
    if(isset($filters['user_id']))
        $sth->bindParam(':user_id',$filters['user_id']);
    if(isset($filters['start_date']))
        $sth->bindParam(':start_delivery_date',$filters['start_date']);
    if(isset($filters['end_date']))
        $sth->bindParam(':end_delivery_date',$filters['end_date']);
    if(isset($filters['delivery_method']))
        $sth->bindParam(':delivery_method',$filters['delivery_method']);

    $orderArray = array();
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo[2]);
    }
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row)
    {
        $orderArray[] = $row;
        $idx++;
    }

    return array('orders' => $orderArray);
}

/*
    Adds optional parameters to query string 
*/
function GetOptionalParameters($filters)
{
    $optionalParams = array();;
    if(isset($filters['order_id']))
    {
        $optionalParams[] = "o.order_id = :order_id ";
    }
    if(isset($filters['status']))
    {
        $optionalParams[] = "o.status = :status ";
    }
    if(isset($filters['user_id']))
    {
        $optionalParams[] = "u.id = :user_id ";
    }
    if(isset($filters['start_date']))
    {
        $optionalParams[] = "o.delivery_date >= :start_delivery_date ";
    }
    if(isset($filters['end_date']))
    {
        $optionalParams[] = "o.delivery_date <= :end_delivery_date ";
    }
    if(isset($filters['delivery_method']))
    {
        $optionalParams[] = "o.delivery_method <= :delivery_method ";
    }

    if(count($optionalParams) > 0)
    {
        return " WHERE " . implode("AND ",$optionalParams);
    }
    return '';
}

function get_order_detail($values)
{
    if(!isset($values['order_id']))
    {
        throw new Exception("Must provide order_id");
    }
    $dbh = new PDOConnection();

    $orderHeader = get_orders(array('order_id' => $values['order_id']));
    $query = "SELECT 
            od.id line_key, 
            od.line_id,
            od.price unit_price, 
            quantity, 
            p.id product_id, 
            p.code product_code, 
            p.description product_description, 
            unit_id, 
            u.code unit_code, 
            u.description unit_description,
            (od.price * od.quantity) line_price
        FROM order_details od 
        LEFT JOIN products p ON od.product_id = p.id 
        LEFT JOIN units u ON unit_id = u.id 
        WHERE order_id = :order_id ";

    $sth = $dbh->prepare($query);
    $sth->bindParam(':order_id',$values['order_id']);
    $detailArray = array();
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo[2]);
    }
    $line_id = 0;
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $row['line_id'] = $line_id++;
        $detailArray[] = $row;
    }
    return array('order_details' => $detailArray);
}
