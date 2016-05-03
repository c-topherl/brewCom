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

    $query = "SELECT o.order_id, 
            total_price, 
            order_date,
            ship_date,
            delivery_date,
            dm.id delivery_method_id,
            dm.description delivery_method,
            user_id, 
            status
            ";

    if(isset($filters['details']))
    {
        $query .= ",comments order_comments, 
                shipping_comments,
                u.username,
                dm.code delivery_method_code,

                billing.name bill_to_name,
                billing.address1 bill_to_addr1,
                billing.address2 bill_to_addr2,
                billing.zipcode bill_to_zip,
                billing.city bill_to_city,
                billing.state bill_to_state,
                shipping.name ship_to_name,
                shipping.address1 ship_to_addr1,
                shipping.address2 ship_to_addr2,
                shipping.zipcode ship_to_zip,
                shipping.city ship_to_city,
                shipping.state ship_to_state
            ";
    }

    $query .= " FROM orders o LEFT JOIN users u ON o.user_id = u.id "
            . " LEFT JOIN delivery_methods dm on delivery_method = dm.id ";

    if(isset($filters['details']))
    {
        $query .= " LEFT JOIN order_addresses billing on billing.order_id = o.order_id and billing.type = 1"
                . " LEFT JOIN order_addresses shipping on shipping.order_id = o.order_id and shipping.type = 0";
    }

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
        throw new Exception("ERROR: failed to retrieve header information - ".$sth->errorInfo()[2]);
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

    //get header information - this returns array of orders, so just grab the first one
    $result = get_orders(array('order_id' => $values['order_id'], 'details' => 1));
    $orderHeader = $result['orders'][0];
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
        throw new Exception("ERROR: Could not retrieve order lines - ".$sth->errorInfo[2]);
    }
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $detailArray[] = $row;
    }
    $orderDetails = $orderHeader;
    $orderDetails['lines'] = $detailArray;
    return $orderDetails;
}
