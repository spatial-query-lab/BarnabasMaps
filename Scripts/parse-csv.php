<?php 
require_once "../Library/DBHelper.php";
$db = new DBHelper();

// Index is not defined
if(!isset($_POST["index"]))
{
    echo -1;
}

$position = 0;
$dir = '../Data/CSVs/';
$plant = '';
$data = array(
    'longitude' => '',
    'latitude' => '',
    'plant_id' => ''
);

// Scanning directory and changing the current working directory
$files = scandir($dir);
chdir('../Data/CSVs/');

// Reading csv file
preg_match_all('/\S+/m', file_get_contents($files[$position]) , $contents, PREG_SET_ORDER, 0);

main();

function main()
{
    foreach($contents as $row)
    {
        // Skip first three rows
        if($row[0] == 'lon' || $row[0] == 'lat' || $row[0] == 'name')
        {
            continue;
        }

        // Insert layer
        if(isFull())
        {
            continue;
        }

        fillData($row[0]);
        
        var_dump($data);
    }
}

function isFull()
{
    return ($data["longitude"] != '' && $data["latitude"] != '');
}

function fillData($value)
{
    if(is_numeric($value))
    {
        insertNumber($value);
    }
}

function insertNumber($value)
{
    if($position != 0 || $position != 1)
    {
        $position = 0
    }

    switch(position)
    {
        // Longitude
        case 0:
            $data["longitude"] = (double) $value;
            break;

        // Latitude
        case 1:
            $data["latitude"] = (double) $value;
            break;

        default:
            break;
    }
    
    $position++;
}
?>
