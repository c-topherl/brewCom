<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

include_once __DIR__ .'/vendor/autoload.php';
use \Products\Product;
use \Util\PDOConnection;

$product = new Product();

switch($function)
{
    case 'add_product':
        $responseArray['response'] = $product->addProduct($values);
        $responseArray['message'] = 'Successfully added product';
        break;
    case 'get_products':
        $responseArray['response'] = $product->getProducts($values);
        $responseArray['message'] = 'Products successfully read';
        break;
    case 'update_product':
        $product->updateProduct($values);
        $responseArray['message'] = 'Product successfully updated';
        break;
    case 'add_product_class':
        $product->addProductClass($values);
        $responseArray['message'] = 'Class successfully added';
        break;
    case 'get_product_classes':
        $responseArray['response'] = $this->getProductClasses($values);
        $responseArray['message'] = 'Classes successfully read';
        break;
    case 'update_product_class':
        $this->updateProductClass($values);
        $responseArray['message'] = 'Product class successfully updated';
        break;
    case 'add_unit':
        $product->addUnit($values);
        $responseArray['message'] = 'Unit successfully added';
        break;
    case 'get_units':
        $responseArray['response'] = $product->getUnits($values);
        $responseArray['message'] = 'Units successfully read';
        break;
    case 'update_unit':
        $responseArray['response'] = $product->updateUnit($values);
        $responseArray['message'] = 'Successfully updated unit';
        break;
    case 'add_product_unit':
        $product->addProductUnit($values);
        $responseArray['message'] = 'Product/unit successfully added';
        break;
    case 'get_product_units':
        $responseArray['response'] = $produce->getProductUnits($values);
        $responseArray['message'] = 'Product/Units successfully read';
        break;
    case 'update_inventory':
        $responseArray['response'] = $product->updateInventory($values);
        $responseArray['message'] = 'Inventory updated';
        break;
    case 'add_warehouse':
        $responseArray['response'] = $product->addWarehouse($values);
        $responseArray['message'] = 'Added warehouse';
        break;
    case 'get_warehouses':
        $responseArray['response'] = $product->getWarehouses($values);
        $responseArray['message'] = 'Got warehouses';
        break;
    case 'update_warehouse':
        $responseArray['response'] = $product->updateWarehouse($values);
        $responseArray['message'] = 'Updated warehouse';
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
