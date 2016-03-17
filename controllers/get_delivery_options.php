<?php
//future enhancement
function get_delivery_options($optionsArray = NULL)
{
    //not supported yet
    $customer_id = isset($optionsArray['customer_id']) ? $optionsArray['customer_id'] : '';
    $datetime = new DateTime('tomorrow');
    return array('delivery_dates' => $datetime->format('Ymd'), 
        'delivery_methods' => array("pickup"),
        'warehouses' => array('warehouses not supported')
        );
}
