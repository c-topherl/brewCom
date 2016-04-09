<?php
define('LOG_FILE', '/home1/joelmeis/public_html/brewCom/logs/order_controller.log');

function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
    file_put_contents(LOG_FILE, print_r($responseArray, true), FILE_APPEND);
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
    $values = file_get_contents('php://input');
    $values = (array)json_decode($values);
    $function = $values['function'];
}
//TODO convert all of these to safe queries
//file_put_contents(LOG_FILE, print_r($_SERVER,true), FILE_APPEND);
//file_put_contents(LOG_FILE, print_r($values,true), FILE_APPEND);

switch($function)
{
    case "add_cart_header":
        include "orders/add_cart.php";
        add_cart_header($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart header created successfully.';
        break;
    case "add_cart_detail":
        include "orders/add_cart.php";
        add_cart_detail($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart detail added successfully.';
        break;
    case "get_cart":
        include "orders/get_cart.php";
        $responseArray['response'] = get_cart($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Here is your cart';
        break;
    case "update_cart_header":
        include "orders/update_cart.php";
        $responseArray['response'] = update_cart_header($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Cart successfully updated';
        break;
    case "delete_cart_detail":
        include "orders/delete_cart.php";
        $responseArray['response'] = delete_cart_detail($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Successfully delete cart line';
        break;
    case "submit_order":
        include "orders/submit_order.php";
        $responseArray['response'] = submit_order($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = 'Order successfully submited';
        break;
    case "add_order":
        include "orders/add_order.php";
        $responseArray['response'] = add_order($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Added order";
        break;
    case "get_orders":
        include "orders/get_orders.php";
        $responseArray['response'] = get_orders($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Orders successfully read";
        break;
    case "get_order_detail":
        include "orders/get_orders.php";
        $responseArray['response'] = get_order_detail($values);
        $responseArray['status'] = 'success';
        $responseArray['message'] = "Order details successfully read";
        break;
    case "get_delivery_options":
        include "orders/get_delivery_options.php";
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
