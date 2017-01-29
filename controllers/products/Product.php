<?php

namespace Products;
use \Util\PDOConnection;
use \PDO;

class Product
{
    function addProduct($productArray)
    {
        $dbh = new PDOConnection();
        $code = $productArray['code'];
        $description = $productArray['description'];
        $price = $productArray['price'];
        $class_id = $productArray['class_id'];
        if($this->checkProductExists($dbh,$class_id))
        {
            throw new Exception("Product code already exists");
        }
        $class_id = getClassId($dbh,$class_id);
        $query = "INSERT INTO products(description,code,price,class) VALUES(:description,:code,:price,:class_id)";
        $sth = $dbh->prepare($query);
        $parameters = array(':description' => $description,
            ':code' => $code,
            ':price' => $price,
            ':class_id' => $class_id);
        if(!$sth->execute($parameters))
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return array('id' => $dbh->lastInsertId());
    }
    //true mean product exists
    function checkProductExists($dbh,$code)
    {
        $query = "SELECT code FROM products WHERE code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        $sth->execute();
        return ($sth->rowCount() > 0);
    }
    function getClassId($dbh,$class_id)
    {
        $query = "SELECT id FROM product_classes WHERE id = :class_id";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':class_id', $class_id);
        $sth->execute();
        if($sth->rowCount() <= 0)
        {
            throw new Exception("Product class id: ".$class_id." does not exist!");
        }
        $row = $sth->fetch();
        return $row['id'];;
    }
//optional product info to read info by code
//TODO add optional parameter support
    function getProducts($info = NULL, &$error = NULL)
    {
        $dbh = new PDOConnection();
        $query = "SELECT p.id product_id, p.code product_code, p.description product_desc, p.price, pc.id class_id, pc.code class_code, pc.description class_desc 
        FROM products p 
        LEFT JOIN product_classes pc ON p.class = pc.id ";
        $optionalParams = '';
        $code = '';
        if(isset($info['code']))
        {
            $optionalParams .= 'p.code = :prod_code ';
            $code = $info['code'];
        }
        if($optionalParams != '')
        {
            $query .= "WHERE " . $optionalParams;
        }
        $sth = $dbh->prepare($query);
        $paramArray = array(
            ':prod_code' => $code
        );
        $sth->execute($paramArray);
        $productArray = array();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row)
        {
            $productArray[] = $row;
        }
        return array('products' => $productArray);
    }
//At least needs ID passed in.  If you don't know it, get it from the get_products() routine
// This is so we can update a product code (since we store id on everything, don't change that)
    function updateProduct($prodInfo)
    {
        if(!isset($prodInfo['id']))
        {
            throw new Exception("Product id required.");
        }
        $dbh = new PDOConnection();
        $query = "SELECT id,code,description,price,active,last_updated FROM products WHERE id = :id";
        $sth = $dbh->prepare($query);
        $id = $prodInfo['id'];
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        if(!($sth->execute()))
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        if(!($oldValues = $sth->fetch()))
        {
            throw new Exception("Product id: '".$id."' not found!");
        }
        $query = "UPDATE products 
        SET code = :code, 
            description = :description, 
            price = :price, 
            class = :class, 
            active = :active 
        WHERE id = :id";
        $sth = $dbh->prepare($query);

        $code = isset($prodInfo['code'])? $prodInfo['code'] : $oldValues['code'];
        $description = isset($prodInfo['description'])? $prodInfo['description'] : $oldValues['description'];
        $price = isset($prodInfo['price'])? $prodInfo['price'] : $oldValues['price'];
        $class = isset($prodInfo['class'])? $prodInfo['class'] : $oldValues['class'];
        $active = isset($prodInfo['active'])? $prodInfo['active'] : $oldValues['active'];

        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':code', $code);
        $sth->bindParam(':description', $description);
        $sth->bindParam(':price', $price);
        $sth->bindParam(':class', $class, PDO::PARAM_INT);
        $sth->bindParam(':active', $active, PDO::PARAM_INT);
        $sth->execute();
        return true;
    }
    /*
    INPUTS:
    code
    description
    */
    function addProductClass($classArray)
    {
        $dbh = new PDOConnection();
        $code = $classArray['code'];
        $description = $classArray['description'];

        $query = "SELECT code FROM product_classes where code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        $sth->execute();
        if($sth->rowCount() > 0)
        {
            throw new Exception("Class code exists");
        }

        $query = "INSERT INTO product_classes(description,code) VALUES(:description,:code)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        $sth->bindParam(':description', $description, PDO::PARAM_STR);
        return $sth->execute();
    }
//TODO:
//classFilters to be expanded later
    function getProductClasses($classFilters = NULL)
    {
        $dbh = new PDOConnection();
        $query = "SELECT * FROM product_classes ";
        $classArray = array();
        $sth = $dbh->prepare($query);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row)
        {
            $classArray[] = $row;
        }
        return $classArray;
    }
    /*
    INPUTS:
    id
    code
    description
    */
//At least needs ID passed in.  If you don't know it, get it from the get_product_classes() routine
// This is so we can update a product code (since we store id on everything, don't change that)
    function updateProductClass($classArray)
    {
        $dbh = new PDOConnection();
        $query = "SELECT id,code,description,last_updated FROM product_classes WHERE id = :id";
        $sth = $dbh->prepare($query);
        $id = $classArray['id'];
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        if(!($oldValues = $sth->fetch()))
        {
            throw new Exception("Class id: '".$id."' not found!");
        }

        $query = "UPDATE product_classes SET code = :code, description = :description WHERE id = :id";
        $sth = $dbh->prepare($query);
        $code = isset($classArray['code'])? $classArray['code'] : $oldValues['code'];
        $description = isset($classArray['description'])? $classArray['description'] : $oldValues['description'];
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':code', $code);
        $sth->bindParam(':description', $description);
        $sth->execute();
        return true;
    }
    /*
    code
    description
    */
    function addUnit($unitArray)
    {
        $dbh = new PDOConnection();
        $code = $unitArray['code'];
        $description = $unitArray['description'];

        $query = "SELECT id,code FROM units WHERE code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        if($sth->rowCount() > 0)
        {
            throw new Exception("Unit code exists");
        }

        $query = "INSERT INTO units(code, description) VALUES(:code, :description)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        $sth->bindParam(':description', $description, PDO::PARAM_STR);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return true;
    }
    function getUnits($filters = NULL)
    {
        $dbh = new PDOConnection();
        $query = "SELECT id, code, description, active, last_updated FROM units ";
        $query .= getOptionalParams($filters);
        $units = array();
        $sth = $dbh->prepare($query);
        if(isset($filters['id']))
        {
            $sth->bindParam(':id', $filters['id'], PDO::PARAM_INT);
        }
        elseif(isset($filters['code']))
        {
            $sth->bindParam(':code', $filters['code']);
        }

        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row)
        {
            $units[] = $row;
        }
        return $units;
    }
    /*
    build optional query string
    TODO:
    filters to be expanded later i.e. code/active
    */
    function getOptionalParams($filters)
    {
        $query = '';
        if(isset($filters['id']))
        {
            $query .= "WHERE id = :id";
        }
        elseif(isset($filters['code']))
        {
            $query .= "WHERE code = :code";
        }
        return $query;
    }
//At least needs ID passed in.  If you don't know it, get it from the get_products() routine
// This is so we can update a product code (since we store id on everything, don't change that)
    function updateUnit($unitInfo)
    {
        if(!isset($unitInfo['id']))
        {
            throw new Exception("Product id required.");
        }
        $id = $unitInfo['id'];
        $dbh = new PDOConnection();
        $oldValues = get_units(array('id' => $id))[0]; //returns array of units
        if(empty($oldValues))
        {
            throw new Exception("Product id: '".$id."' not found!");
        }

        $query = "UPDATE units 
        SET code = :code, 
            description = :description, 
            active = :active
        WHERE id = :id";
        $sth = $dbh->prepare($query);

        $code = isset($unitInfo['code'])? $unitInfo['code'] : $oldValues['code'];
        $description = isset($unitInfo['description'])? $unitInfo['description'] : $oldValues['description'];
        $active = isset($unitInfo['active']) ? $unitInfo['active'] : $oldValues['active'];

        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':code', $code);
        $sth->bindParam(':description', $description);
        $sth->bindParam(':active', $active, PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return true;
    }
    /*
    INPUTS:
    product_code/product_id
    unit_code/unit_id

    note: passing id will be faster
    */
    function addProductUnit($info)
    {
        $dbh = new PDOConnection();
        $product_id = isset($info['product_id']) ? $info['product_id'] : '';
        $unit_id = isset($info['unit_id']) ? $info['unit_id'] : '';
        $description = isset($info['description']) ? $info['description'] : '';
        if(!$product_id)
        {
            $product_code = isset($info['product_code']) ? $info['product_code'] : '';
            if(!$product_code)
            {
                throw new Exception("Product id or code required");
            }
            $query = "SELECT id FROM products WHERE code = :code";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':code',$product_code);
            if(!$sth->execute())
            {
                throw new Exception($sth->errorInfo()[2]);
            }
            $product_id = $sth->fetchColumn();
        }
        if(!$unit_id)
        {
            $unit_code = isset($info['product_code']) ? $info['unit_code'] : '';
            if(!$unit_code)
            {
                throw new Exception("Unit id or code required");
            }
            $query = "SELECT id FROM units WHERE code = :code";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':code',$unit_code);
            if(!$sth->execute())
            {
                throw new Exception($sth->errorInfo[2]);
            }
            $unit_id = $sth->fetchColumn();
        }

        $query = "SELECT id FROM product_unit WHERE product_id = :pid AND unit_id = :uid";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $sth->bindParam(':uid', $unit_id, PDO::PARAM_INT);
        $sth->execute();
        if($sth->rowCount() > 0)
        {
            throw new Exception("Product/unit entry already exists.");
        }

        $query = "INSERT INTO product_unit(product_id,unit_id,description) VALUES(:pid,:uid,desc)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $sth->bindParam(':uid', $unit_id, PDO::PARAM_INT);
        $sth->bindParam(':description', $description, PDO::PARAM_STR);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return true;
    }
//optional product info to read info by product code
//TODO finish optional parameters and binding paramters
    function getProductUnits($info = NULL)
    {
        $dbh = new PDOConnection();
        $query = "SELECT 
                pu.id product_unit_id,
                p.id product_id, 
                p.code product_code, 
                p.description product_description, 
                pu.price, 
                pc.id class_id, 
                pc.code class_code, 
                pc.description class_description, 
                u.id unit_id, 
                u.code unit_code, 
                u.description unit_description 
            FROM product_unit pu
            LEFT JOIN units u ON pu.unit_id = u.id 
            LEFT JOIN products p ON pu.product_id = p.id 
            LEFT JOIN product_classes pc ON p.class = pc.id ";
        $optionalParams = array();
        if(isset($info['product_code']))
        {
            $optionalParams[] = 'p.code = :product_code ';
            $product_code = $info['product_code'];
        }
        if(count($optionalParams) > 0)
        {
            $query .= "WHERE ";
            $query .= implode("AND ",$optionalParams);
        }
        $sth = $dbh->prepare($query);
        if(isset($product_code))
            $sth->bindParam(':product_code',$product_code);

        $sth->execute();
        $productArray = array();
        foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $productArray[] = $row;
        }
        return array('product_units' => $productArray);
    }
    /*
    There is no add/delete inventory.  we will just insert values and then on duplicate key update
    */
    function updateInventory($inventoryInfo)
    {
        if(!isset($inventoryInfo['inventory']))
        {
            throw new Exception('Must provide \'inventory\'');
        }

        $dbh = new PDOConnection();
        $query = "INSERT INTO inventory(
                product_id, unit_id, quantity
            )
            VALUES(
                :product_id, :unit_id, :quantity
            )
            ON DUPLICATE KEY UPDATE
                quantity = :quantity";

        $product_id = -1;
        $unit_id = -1;
        $qantity = -1;
        $response = '';
        $quantity = 0;

        $sth = $dbh->prepare($query);

        $sth->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $sth->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
        $sth->bindParam(':quantity', $quantity);

        foreach($inventoryInfo['inventory'] as $inventory)
        {
            $product_id = $inventory['product_id'];
            $unit_id = $inventory['unit_id'];
            $quantity = $inventory['quantity_id'];
            if(!$sth->execute())
            {
                throw new Exception($sth->errorInfo()[2]);
            }
        }
        return true;
    }
    function addWarehouse($info)
    {
        //TODO
        if(!(isset($info['code']) && isset($info['name'])))
        {
            throw new Exception("Must provide code and name");
        }
        $dbh = new PDOConnection();
        //Throws exception when exists
        CheckWarehouseCodeExists($dbh, $info['code']);

        $info = GetDefaultWarehouseInfo($info);
        $info['id'] = AddWarehouseHelper($dbh, $info);
        return $info;
    }

    function CheckWarehouseCodeExists($dbh, $code)
    {
        $query = "SELECT id,code FROM warehouses WHERE code = :code";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $code, PDO::PARAM_STR);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        if($sth->rowCount() > 0)
        {
            throw new Exception("Warehouse code exists");
        }

        //false = warehouse code doesnt exist
        return FALSE;
    }
    function GetDefaultWarehouseInfo($info)
    {
        $info += array('address1' => '', 'address2' => '', 'city' => '', 'state' => '', 'zipcode' => '', 'delivery_allowed' => 1, 'active' => 1);
        return $info;
    }
    function AddWarehouseHelper($dbh, $info)
    {
        $query = "INSERT INTO warehouses(code, name, address1, address2, city, state, zipcode, delivery_allowed, active) VALUES(:code, :name, :address1, :address2, :city, :state, :zipcode, :delivery_allowed, :active)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':code', $info['code'], PDO::PARAM_STR);
        $sth->bindParam(':name', $info['name'], PDO::PARAM_STR);
        $sth->bindParam(':address1', $info['address1'], PDO::PARAM_STR);
        $sth->bindParam(':address2', $info['address2'], PDO::PARAM_STR);
        $sth->bindParam(':city', $info['city'], PDO::PARAM_STR);
        $sth->bindParam(':state', $info['state'], PDO::PARAM_STR);
        $sth->bindParam(':zipcode', $info['zipcode'], PDO::PARAM_STR);
        $sth->bindParam(':delivery_allowed', $info['delivery_allowed'], PDO::PARAM_STR);
        $sth->bindParam(':active', $info['active'], PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return $dbh->lastInsertId();
    }
    function getWarehouses($opts = NULL)
    {
        $dbh = new PDOConnection();

        //not supported yet
        $customer_id = isset($opts['customer_id']) ? $opts['customer_id'] : '';

        //not supported yet
        $query = "SELECT id, code, name, address1, address2, city, state, zipcode, delivery_allowed, active, last_updated FROM warehouses ";
        if(isset($opts['id']))
        {
            $query .= " WHERE id = :id";
        }

        $sth = $dbh->prepare($query);

        if(isset($opts['id']))
        {
            $sth->bindParam(':id', $opts['id'], PDO::PARAM_INT);
        }

        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }

        $warehouses = array();
        foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $warehouses[] = $row;
        }
        return $warehouses;
    }
//id required
    function updateWarehouse($info)
    {
        //TODO
        throw new Exception("Not implemented");

        if(!isset($info['id']))
        {
            throw new Exception("Product id required.");
        }
        $id = $info['id'];
        $dbh = new PDOConnection();
        CheckWarehouseIdExists($dbh, $info['id']);
        $info = GetDefaultWarehouseInfo($info);
        UpdateWarehouseHelper($dbh, $info);
    }
    function GetOldWarehouseInfo($info)
    {
        return $info;
    }
    function UpdateWarehouseHelper($dbh, $info)
    {
        return false;

        $query = "UPDATE warehouses 
        SET code = :code, 
            description = :description, 
            active = :active
                WHERE id = :id";
        $sth = $dbh->prepare($query);

        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':code', $code);
        $sth->bindParam(':description', $description);
        $sth->bindParam(':active', $active, PDO::PARAM_INT);
        if(!$sth->execute())
        {
            throw new Exception($sth->errorInfo()[2]);
        }
        return true;
    }
}