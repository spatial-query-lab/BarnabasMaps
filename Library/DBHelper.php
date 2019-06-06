<?php
//NOTE:
/*
- BEFORE EXECUTING QUERIES OR CALL SPs, MUST EITHER:
    + Use prepare statement
    + OR Sanitize input using mysqli_real_escape_string

- BIND PARAMETER: Types: s = string, i = integer, d = double,  b = blob
*/

//PLEASE USE THIS TEMPLATE TO COMMENT ON FUNCTIONS
/**********************************************
Function:
Description:
Parameter(s):
Return value(s):
 ***********************************************/
class DBHelper
{
    //Members
     static protected $ini_dir = "Bandocat_config\\bandoconfig.ini";
     protected $host;
     protected $user;
     protected $pwd;
     protected $maindb;
     protected $conn;

    //Getter and setters

    function __construct()
    {
        $root = substr(getcwd(),0,strpos(getcwd(),"htdocs\\")); //point to xampp// directory
        $config = parse_ini_file($root . DBHelper::$ini_dir);
        $this->host = $config['servername'];
        $this->user = $config['username']; //
        $this->pwd = $config['password'];
        $this->maindb = $config['dbname'];
           /*if not currently connected, attempt to connect to DB*/
           if ($this->getConn() == null)
               $this->DB_CONNECT(null);
    }

    /**
     * @return mixed
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @param mixed $conn
     */
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    /**********************************************
     * Function: DB_CONNECT
     * Description: Connect to XAMPP db. localhost/phpadmin All functions connect to db using this.
     * Parameter(s):
     * $db (string) - name of database to be connected to
     * Return value(s):
     * $conn (object) - return connection object if success
     * - return -1 if failed
     ***********************************************/

    function DB_CONNECT($db)
    {
        if ($db == "" || $db == null) //empty parameter = default = bandocatdb
            $db = "barnabasmaps";
        /* assign conn as a PHP Data Object, concat the host, user and pwd */
        $this->conn = new PDO('mysql:host=' . $this->getHost() . ';dbname=' . $db, $this->getUser(), $this->getPwd());
        return 0;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }


    //Constructor

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    //IMPORTANT: Default Host, username, password can be changed here!

    /**
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**********************************************
     * Function: DB_CLOSE
     * Description: Closes connection to XAMPP db. localhost/phpadmin.
     * Parameter(s):
     * Return value(s):

     ***********************************************/
    function DB_CLOSE()
    {
        $this->setConn(null);
    }

    public function SELECT_TABLE_BY_NAME($tablename)
    {
        if($this->conn == false)
        {
            echo "Could not establish a connection with the server.";
            return false;
        }
        try {
            // Getting database handler
            $dbh = $this->conn;
            $sth = $dbh->prepare('SELECT * FROM ' . $tablename);
            if($sth->execute() == false)
            {
                echo "\nPDO::errorInfo():\n";
                print_r($sth->errorInfo());
                return false;
            }
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            // Closing connection
            $dbh = null;
            $sth = null;
            return $result;
        } catch(PDOException $exception) {
            return false;
        }
    }

    public function GET_COORDINATES($plantid)
    {
        if($this->conn == false)
        {
            echo "Could not establish a connection with the server.";
            return false;
        }
        try {
            // Getting database handler
            $dbh = $this->conn;
            $sth = $dbh->prepare('SELECT * FROM `layers` WHERE `plant_id` = :plantid');
            $sth->bindParam(':plantid', $plantid, PDO::PARAM_INT);

            if($sth->execute() == false)
            {
                echo "\nPDO::errorInfo():\n";
                print_r($sth->errorInfo());
                return false;
            }
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            // Closing connection
            $dbh = null;
            $sth = null;
            return $result;
        } catch(PDOException $exception) {
            return false;
        }
    }

    public function INSERT_REPORT($plantid, $number = 1, $lon, $lat)
    {
        if($this->conn == false)
        {
            echo "Could not establish a connection with the server.";
            return false;
        }
        try {
            // Getting database handler
            $dbh = $this->conn;
            $sth = $dbh->prepare('INSERT INTO `reports` (`plant_id`, `number`, `longitude`, `latitude`) VALUES (:plantid, :number, :lon, :lat)');
            $sth->bindParam(':plantid', $plantid, PDO::PARAM_INT);
            $sth->bindParam(':number', $number, PDO::PARAM_INT);
            $sth->bindParam(':lon', $lon, PDO::PARAM_STR, strlen($lon));
            $sth->bindParam(':lat', $lat, PDO::PARAM_STR, strlen($lat));
            
            if($sth->execute() == false)
            {
                echo "\nPDO::errorInfo():\n";
                print_r($sth->errorInfo());
                return false;
            }
            $result = $dbh->lastInsertId();
            // Closing connection
            $dbh = null;
            $sth = null;
            return $result;
        } catch(PDOException $exception) {
            return false;
        }
    }
}