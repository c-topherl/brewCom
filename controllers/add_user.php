<?php
require_once("DBConnection.php");
function add_user(&$error = null)
{
    $dbConn = new DBConnection();
    $dbConn->db_connect();
    $username = $_POST['username'];
    $email = $_POST['email'];
//check exists
    $sql = "SELECT username,email FROM users WHERE username = '".mysqli_real_escape_string($dbConn,$username)."' OR email = '".mysqli_real_escape_string($dbConn,$email)."'";
    if($result = $dbConn->db_query($sql))
    {
        if($row = mysqli_fetch_assoc($result))
        {
            if($row['email'] === $email)
            {
                $error "Email already exists";
                return false;
            }
            elseif($row['username'] === $username)
            {
                $error "Username already exists";
                return false;
            }       
        }
    }
//add some salt
    $password = hash('sha256',hash('sha256',$_POST['password']).$username);
    $sql = "INSERT INTO users(username,email,password) VALUES('$username','$email','$password')";
    if(!$dbConn->db_query($sql))
    {
        echo mysqli_error($dbConn->get_con());
    }
    return true;
}
