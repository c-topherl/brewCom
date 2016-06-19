<?php
require_once("PDOConnection.php");
function get_customers($values = NULL)
{
    $dbh = new PDOConnection();
    $query = "SELECT id, code, name, active, last_updated FROM customers ";

    if(isset($values['id']))
    {
        $optional[] = "id = :id ";
    }
    if(isset($values['code']))
    {
        $optional[] = "code = :code ";
    }
    if(isset($values['active']))
    {
        $optional[] = "active = :active ";
    }

    if(!empty($optional))
    {
        $query .= ' WHERE ';
        $countOpt = count($optional);
        for($i = 0 ; $i < $countOpt; ++$i)
        {
            $query .= (($i > 0) ? ' AND ' : ' ') . $optional[$i];
        }
    }

    $sth = $dbh->prepare($query);
    if(isset($values['id']))
    {
        $sth->bindParam(':id', $values['id'], PDO::PARAM_INT);
    }
    if(isset($values['code']))
    {
        $sth->bindParam(':code', $values['code'], PDO::PARAM_STR);
    }
    if(isset($values['active']))
    {
        $sth->bindParam(':active', $values['active'], PDO::PARAM_INT);
    }
    if(!$sth->execute())
    {
        throw new Exception($sth->errorInfo()[2]);
    }

    $customerArray = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $customerArray[] = $row;
    }
    return array('customers' => $customerArray);
}
