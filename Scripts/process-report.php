<?php 
require_once "../Library/DBHelper.php";
$db = new DBHelper();

$result = $db->INSERT_REPORT($_POST["plantid"], $_POST["number"], $_POST["longitude"], $_POST["latitude"]);

if($result != false)
{
    echo "Success!";
}

else
{
    echo "Error!";
}
?>