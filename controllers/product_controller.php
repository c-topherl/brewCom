<?php
function exception_handler($e)
{
    $responseArray['status'] = 'failure';
    $responseArray['message'] = $e->getMessage();
    echo json_encode($responseArray);
}
set_exception_handler('exception_handler');

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
switch($function)
{
    case "add_product":
        include "add_product.php";
        $responseArray['response'] = add_product($values);
        $responseArray['message'] = "Successfully added product";
        break;
    case "get_products":
        include "get_products.php";
        $responseArray['response'] = get_products($values);
        $responseArray['message'] = "Products successfully read";
        break;
    case "update_product":
        include "update_product.php";
        update_product($values);
        $responseArray['message'] = "Product successfully updated";
        break;
    case "add_product_class":
        include "add_product_class.php";
        add_product_class($values);
        $responseArray['message'] = "Class successfully added";
        break;
    case "get_product_classes":
        include "get_product_classes.php";
        $responseArray['response'] = get_product_classes($values);
        $responseArray['message'] = "Classes successfully read";
        break;
    case "update_product_class":
        include "update_product_class.php";
        update_product_class($values);
        $responseArray['message'] = "Product class successfully updated";
        break;
    case "add_unit":
        include "add_unit.php";
        add_unit($values);
        $responseArray['message'] = "Unit successfully added";
        break;
    case "get_units":
        include "get_units.php";
        $responseArray['response'] = get_units($values);
        $responseArray['message'] = "Units successfully read";
        break;
    case "add_product_unit":
        include "add_product_unit.php";
        add_product_unit($values);
        $responseArray['message'] = "Product/unit successfully added";
        break;
    case "get_product_units":
        include "get_product_units.php";
        $responseArray['response'] = get_product_units($values);
        $responseArray['message'] = "Product/Units successfully read";
        break;
    default:
        throw new Exception("Unknown function: $function.");
}
$responseArray['status'] = 'success';
if($_SERVER['SCRIPT_NAME'] === 'testproductcontroller.php')
{
    return $responseArray;
}
echo json_encode($responseArray);
exit();
?>
