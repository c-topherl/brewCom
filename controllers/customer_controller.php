<?php
function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
}
set_exception_handler('exception_handler');

// This file will have all the functions to interact with orders (creation, read, etc)
$function = 'unknown';
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

switch($function)
{
    case "add_address";
        include "customers/add_address.php";
        $responseArray['response'] = add_address($values);
        $responseArray['message'] = "Address added";
        break;
    case "get_addresses";
        include "customers/get_addresses.php";
        $responseArray['response'] = get_addresses($values);
        $responseArray['message'] = "Addresses got";
        break;
    case "update_address";
        include "customers/update_address.php";
        $responseArray['response'] = update_address($values);
        $responseArray['message'] = "Address updated";
        break;
    case "add_user":
        include "customers/add_user.php";
        $responseArray['response'] = add_user($values);
        $responseArray['message'] = "User successfully added";
        break;
    case "get_users":
        include "customers/get_users.php";
        $responseArray['response'] = get_users($values);
        $responseArray['message'] = "Users successfully read";
        break;
    case "update_user":
        include "customers/update_user.php";
        $responseArray['response'] = update_user($values);
        $responseArray['message'] = "User updated.";
        break;
    case "add_customer":
        include "customers/add_customer.php";
        $responseArray['response'] = add_customer($values);
        $responseArray['message'] = "Customer successfully added";
        break;
    case "update_customer":
        include "customers/update_customer.php";
        $responseArray['response'] = update_customer($values);
        $responseArray['message'] = "Customer updated.";
        break;
    case "get_customers":
        include "customers/get_customers.php";
        $responseArray['response'] = get_customers($values);
        $responseArray['message'] = "Customers successfully read";
        break;
    case "verify_user":
        include "customers/verify_user.php";
        $responseArray['response'] = verify_user($values);
        $responseArray['message'] = "User verified";
        break;
    case "verify_admin":
        include "customers/verify_admin.php";
        $responseArray['response'] = verify_admin($values);
        $responseArray['message'] = "Admin verified";
        break;
    default:
        throw new Exception("Unknown function: $function");
}

$responseArray['status'] = 'success';
echo json_encode($responseArray);
exit();
?>
