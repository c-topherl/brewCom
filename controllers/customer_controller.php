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
    case "verify_user":
        include "customers/verify_user.php";
        $responseArray['response'] = verify_user($values);
        $responseArray['message'] = "User verified";
        break;
    default:
        throw new Exception("Unknown function: $function");
}

$responseArray['status'] = 'success';
echo json_encode($responseArray);
exit();
?>
