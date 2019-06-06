<?php
require_once "../Library/DBHelper.php";
$db = new DBHelper();

if(!isset($_POST["plantid"]))
{
    return false;
}

$layers = $db->GET_COORDINATES($_POST["plantid"]);
$coordinates = array();

foreach($layers as $layer)
{
    array_push($coordinates, [(double) $layer["longitude"], (double) $layer["latitude"]]);
}

echo json_encode($coordinates);
?>