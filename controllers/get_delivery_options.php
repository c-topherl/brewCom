<?php
//future enhancement
function get_delivery_options($optionsArray = NULL)
{
    //not supported yet
    $customer_id = isset($optionsArray['customer_id']) ? $optionsArray['customer_id'] : '';
    $datetime = new DateTime('tomorrow');
    $deliveryOptions = array(
        'delivery_dates' => array("title" => $datetime->format('m/d/Y'), "value" => $datetime->format('Y-m-d')), 
        'delivery_methods' => array("title" => "Pickup", "value" => 0),
        'warehouses' => array("title" => "default warehouses", "value" => 0)
        );
    return $deliveryOptions;
}
