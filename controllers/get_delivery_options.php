<?php
include_once('PDOConnection.php');
function get_delivery_options($optionsArray = NULL)
{
    $dbh = new PDOConnection();

    //not supported yet
    $customer_id = isset($optionsArray['customer_id']) ? $optionsArray['customer_id'] : '';

    $dates = GetAvailableDates();
    $deliveryMethods = GetDeliveryMethods($dbh);

    //not supported yet
    $warehouses = GetWarehouses($dbh);

    $deliveryOptions = array(
        'delivery_dates' => $dates, 
        'delivery_methods' => $deliveryMethods,
        'warehouses' => $warehouses
        );
    return $deliveryOptions;
}
/*
TODO: implement warehouses
*/
function GetWarehouses($dbh)
{
    $query = "SELECT id, description, name
            FROM warehouses";
    $sth = $dbh->prepare($query);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $warehouses = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $warehouses[] = array(
            'title' => $row['description'],
            'value' => $row['id']
            );
    }
    return $warehouses;
}

/*
    Currently returns all delivery methods.  Restrictions to be added when needed
*/
function GetDeliveryMethods($dbh)
{
    $query = "SELECT id, code, description, last_updated 
            FROM delivery_methods";
    $sth = $dbh->prepare($query);
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }
    $methods = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $methods[] = array(
            'title' => $row['description'],
            'value' => $row['id'],
            'code' => $row['code']
            );
    }
    return $methods;
}

/*
    Currently gets next 7 days starting with tomorrow
    Will extend to add more options
*/
function GetAvailableDates()
{
    $dates = array();
    $datetime = new DateTime('tomorrow');
    for($i=0;$i<6;++$i)
    {
        $dates[] = array("title" => date('Y-m-d', strtotime('tomorrow + '.$i.' day')), 
            "value" => date('m/d/Y', strtotime('tomorrow + '.$i.' day')));
    }
    return $dates;
}
