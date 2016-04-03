<?php
function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
}
set_exception_handler('exception_handler');

// This file will have all the functions to interact with orders (creation, read, etc)
//You are allowed to pass values in either by POST or GET.  Please HTTP responsibly
$function = 'unknown';
$values = array();
if (isset($_POST['function']))
{
    $function = $_POST['function'];
    $values = $_POST;
}
elseif (isset($_GET['function']))
{
    $function = $_GET['function'];
    $values = $_GET;
}
else
{
    $values = (array)json_decode(file_get_contents('php://input'));
    $function = $values['function'];
}
//TODO convert all of these to safe queries
define('LOG_FILE', '/home1/joelmeis/public_html/brewCom/test.log');
//file_put_contents(LOG_FILE, print_r($_SERVER,true), FILE_APPEND);
//file_put_contents(LOG_FILE, print_r($values,true), FILE_APPEND);

switch($function)
{
    case "add_cart_header":
        include "add_cart.php";
        add_cart_header($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart header created successfully.';
        break;
    case "add_cart_detail":
        include "add_cart.php";
        add_cart_detail($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart detail added successfully.';
        break;
    case "get_cart":
        include "get_cart.php";
        $responseArray['response'] = get_cart($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Here is your cart';
        break;
    case "update_cart":
        include "update_cart.php";
        $responseArray['response'] = update_cart($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart successfully updated';
        break;
    case "submit_order":
        include "submit_order.php";
        $responseArray['response'] = submit_order($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Order successfully submited';
        break;
    case "add_order":
        include "add_order.php";
        $responseArray['response'] = add_order($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Added order";
        break;
    case "get_orders":
        include "get_orders.php";
        $responseArray['response'] = get_orders($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Orders successfully read";
        break;
    case "get_order_detail":
        include "get_orders.php";
        $responseArray['response'] = get_order_detail($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Order details successfully read";
        break;
    case "get_delivery_options":
        include "get_delivery_options.php";
        $responseArray['status'] = "success";
        $responseArray['message'] = "This feature is not implemented, but always will return \"pickup\" for now";
        $responseArray['response'] =  get_delivery_options($values);
        break;
    default:
        $responseArray['status'] = 'failure';
        $responseArray['message'] = "Unknown function: $function";
}

echo json_encode($responseArray);
exit();
?>
