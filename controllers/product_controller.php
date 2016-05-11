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
    $values = file_get_contents('php://input');
    $values = (array)json_decode($values);
    $function = $values['function'];
}
switch($function)
{
    case "add_product":
        include "products/add_product.php";
        $responseArray['response'] = add_product($values);
        $responseArray['message'] = "Successfully added product";
        break;
    case "get_products":
        include "products/get_products.php";
        $responseArray['response'] = get_products($values);
        $responseArray['message'] = "Products successfully read";
        break;
    case "update_product":
        include "products/update_product.php";
        update_product($values);
        $responseArray['message'] = "Product successfully updated";
        break;
    case "add_product_class":
        include "products/add_product_class.php";
        add_product_class($values);
        $responseArray['message'] = "Class successfully added";
        break;
    case "get_product_classes":
        include "products/get_product_classes.php";
        $responseArray['response'] = get_product_classes($values);
        $responseArray['message'] = "Classes successfully read";
        break;
    case "update_product_class":
        include "products/update_product_class.php";
        update_product_class($values);
        $responseArray['message'] = "Product class successfully updated";
        break;
    case "add_unit":
        include "products/add_unit.php";
        add_unit($values);
        $responseArray['message'] = "Unit successfully added";
        break;
    case "get_units":
        include "products/get_units.php";
        $responseArray['response'] = get_units($values);
        $responseArray['message'] = "Units successfully read";
        break;
    case "update_unit":
        include "products/update_unit.php";
        $responseArray['response'] = update_unit($values);
        $responseArray['message'] = "Successfully updated unit";
        break;
    case "add_product_unit":
        include "products/add_product_unit.php";
        add_product_unit($values);
        $responseArray['message'] = "Product/unit successfully added";
        break;
    case "get_product_units":
        include "products/get_product_units.php";
        $responseArray['response'] = get_product_units($values);
        $responseArray['message'] = "Product/Units successfully read";
        break;
    case "update_inventory":
        include "products/update_inventory.php";
        $responseArray['response'] = update_inventory($values);
        $responseArray['message'] = "Inventory updated";
        break;
    case "add_warehouse":
        include "products/add_warehouse.php";
        $responseArray['response'] = add_warehouse($values);
        $responseArray['message'] = "Added warehouse";
        break;
    case "get_warehouses":
        include "products/get_warehouses.php";
        $responseArray['response'] = get_warehouses($values);
        $responseArray['message'] = "Got warehouses";
        break;
    case "update_warehouse":
        include "products/update_warehouse.php";
        $responseArray['response'] = update_warehouse($values);
        $responseArray['message'] = "Updated warehouse";
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
