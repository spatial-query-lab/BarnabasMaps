<?php
require_once "DBHelper.php";
class CSVParser extends DBHelper 
{
    private $fileContents;
    private $index;
    private $filename;
    private $numberArray;

    function __construct() {
        parent::__construct();
    }

    /********Setters and Getters*********/
    function getFileContents()
    {
        return $this->fileContents;
    }

    function getIndex()
    {
        return $this->index;
    }

    function getFilename()
    {
        return $this->filename;
    }

    function getNumberArray()
    {
        return $this->numberArray;
    }

    function setFileContents($file)
    {
        $this->fileContents = $file;
    }

    function setIndex($index)
    {
        $this->index = $index;
    }

    function setFilename($filename)
    {
        $this->filename = $filename;
    }

    function setNumberArray()
    {
        $fileContents = $this->getFileContents();
        
        if($fileContents == null || $fileContents == '')
        {
            $this->numberArray = null;
        }

        preg_match_all('/(-\d+.\d+)|(\d+.\d+)|(-\d+)|(\d+)/m',  $fileContents, $this->numberArray);
    }
    /****************************************/

    function INSERT_ALL_NUMBERS()
    {
        $numbers = $this->getNumberArray();
        $position = 0;
        $lon = 0;
        $lat = 0;

        $plantid = $this->FIND_PLANT();

        if($plantid == -1)
        {
            $plantid = $this->INSERT_PLANT();
        }

        foreach($numbers[0] as $number)
        {
            // Longitude
            if($position == 0)
            {
                $lon = $number;
                $position++;
            }

            else if($position == 1)
            {
                $lat = $number;

                if($this->INSERT_LAYER($plantid, $lon, $lat) == false)
                {
                    echo "Insertion failed for $plantid with lat: " . $lon . " and lat: " . $lat . "\n";
                }

                $position = 0;
                $lat = 0;
                $lon = 0;
            }
        }
    }

    function FIND_PLANT()
    {
        // Check to see if the connection failed
        $dbh = $this->getConn();
        $plantName = $this->getFilename();

        if($dbh == false)
        {
            echo "Could not establish connection to database.";
            return false;
        }

        $sth = $dbh->prepare('SELECT `id` FROM `plants` WHERE `name` = :plant LIMIT 1');
        $sth->bindParam(':plant', $plantName, PDO::PARAM_STR, strlen($plantName));

        // Executing query
        if($sth->execute() == false)
        {
            echo "\nPDO::errorInfo():\n";
            print_r($sth->errorInfo());
            return false;
        }

        $result = $sth->fetchColumn();

        // Closing connection
        $dbh = null;
        $sth = null;

        if($result == '')
        {
            // Found nothing
            return -1;
        }

        return (int) $result;
    }

    function INSERT_PLANT()
    {
        // Check to see if the connection failed
        $dbh = $this->getConn();
        $plantName = $this->getFilename();

        if($dbh == false)
        {
            echo "Could not establish connection to database.";
            return false;
        }

        $sth = $dbh->prepare('INSERT INTO `plants` (`name`) VALUES (:plant)');
        $sth->bindParam(':plant', $plantName, PDO::PARAM_STR, strlen($plantName));

        // Executing query
        if($sth->execute() == false)
        {
            echo "\nPDO::errorInfo():\n";
            print_r($sth->errorInfo());
            return false;
        }

        $plantid = $dbh->lastInsertId();

        // Closing connection
        $dbh = null;
        $sth = null;

        return (int) $plantid;
    }

    function INSERT_LAYER($plantid, $lon, $lat)
    {
        // Check to see if the connection failed
        $dbh = $this->getConn();
        $plantName = $this->getFilename();

        if($dbh == false)
        {
            echo "Could not establish connection to database.";
            return false;
        }

        $sth = $dbh->prepare('INSERT INTO `layers` (`plant_id`, `longitude`, `latitude`) VALUES (:plantid, :lon, :lat)');
        $sth->bindParam(':plantid', $plantid, PDO::PARAM_INT);
        $sth->bindParam(':lon', $lon, PDO::PARAM_STR, strlen($lon));
        $sth->bindParam(':lat', $lat, PDO::PARAM_STR, strlen($lat));

        // Executing query
        if($sth->execute() == false)
        {
            echo "\nPDO::errorInfo():\n";
            print_r($sth->errorInfo());
            return false;
        }

        $layerid = $dbh->lastInsertId();

        // Closing connection
        $dbh = null;
        $sth = null;

        return $layerid;
    }
} 
?>