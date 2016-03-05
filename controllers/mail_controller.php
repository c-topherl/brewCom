<?php
function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
}
set_exception_handler('exception_handler');

// This controller sends emails
if(isset($_GET))
{
    $values = $_GET;
}
$function = 'unknown';
if (isset($_GET['function']))
{
    $function = $_GET['function'];
}

include_once "mail.inc";
switch($function)
{
    case "contact_us":
        contact_us($values);
        $responseArray['message'] = "Email has been sent";
        $responseArray['response'] = "Email has been sent";
        break;
    default:
        throw new Exception("Unknown function: $function");
}

$responseArray['status'] = 'success';
echo json_encode($responseArray);
exit();
?>
