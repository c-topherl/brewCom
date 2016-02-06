<?php
require_once("DBConnection.php");
function db_connect($servername="localhost",
        $username="joelmeis_brewery",
        $db_pass="brewcom9876",
        $dbname="joelmeis_brewCom"){

    // create connection
    $con = new mysqli($servername, $username, $db_pass, $dbname);
    if (mysqli_connect_errno($con)){
        $this->output_error('could not connect to database');
    }
    //$this->$connection = $con;
    return $con;
}
function addProduct()
{
    $dbConn = new DBConnection();
    //$conn = $dbConn->db_connect();
    $conn = db_connect();
    $code = $_POST['code'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $sql = "SELECT code FROM products where code = '".mysqli_real_escape_string($conn, $code)."'";
    if($result = mysqli_query($conn,$sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            return false;
        }
        $sql = "INSERT INTO products(description,code,price,type) VALUES('$description','$code',$price,'$type')";
        echo "sql: $sql\n";
        return (mysqli_query($conn,$sql));
    }
    else
    {
        return false;
    }
}
/*
   getVars
   insert in to table
   return 
*/
