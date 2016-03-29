<?php
class DBConnection
{
    private $connection;
    public function get_con()
    {
        return $this->connection;
    }
    public function db_query($sql)
    {
        return mysqli_query($this->connection,$sql);
    }
    public function db_connect($servername="localhost",
            $username="root",
            $db_pass="",
            $dbname="brewCom"){

        // create connection
        $con = new mysqli($servername, $username, $db_pass, $dbname);
        if (mysqli_connect_errno($con)){
            $this->output_error('could not connect to database');
            return null;
        }
        $this->connection = $con;
        return $con;
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function db_error()
    {
        return mysqli_error($this->connection);
    }

    function output_error($errormessage){
        http_response_code(400);
        echo json_encode(array('error' => $errormessage));
        exit();
    }

    function return_success(){
        echo json_encode(array('result' => 'success'));
    }

    function debug_to_console($data) {
        if(is_array($data) || is_object($data)) {
            echo("<script>console.log('php: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('php: $data');</script>");
        }
    }
}
?>
