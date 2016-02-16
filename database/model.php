<?php

require 'appconf.php';

/**
 * this class contain all orm functions
 */
class ORM {

    static $conn;
    private $dbconn;
    protected $table;

    /**
     * static function to get instance of orm
     * @return type
     */
    static function getInstance() {
        if (self::$conn == null) {
            self::$conn = new ORM();
        }
        return self::$conn;
    }

    /**
     * constractor to connect in database
     */
    function __construct() {

        extract($GLOBALS['conf']);
        $this->dbconn = new mysqli($host, $username,$password, $database);
    }

    /**
     * Get connection
     * @return type
     */
    function getConnection() {
        return $this->dbconn;
    }

    /**
     * function to set table name
     * @param type $table
     */
    function setTable($table) {
        $this->table = $table;
    }

    /**
     * this function used to insert query
     * @param type $data
     * @return type
     */
    function insert($data) {
        $query = "insert into $this->table set ";
        foreach ($data as $col => $value) {
            $query .= $col . "= '" . $value . "', ";
        }
        $query[strlen($query) - 2] = " ";
        $state = $this->dbconn->query($query);
        if (!$state) {
            return $this->dbconn->error;
        }

        return $this->dbconn->affected_rows;
    }

    /**
     * this function to select all query
     * @return type
     */
    function select_all() {
        $query = "select * from " . $this->table;
        $state = $this->dbconn->query($query);
        if (!$state) {
            return $this->dbconn->error;
        }
        return $state;
    }

   

    /**
     * Select last row of table with where condition
     * @return type
     */
    function select_last_row($values) {
        $query ="SELECT * FROM " . $this->table." where "; 
        foreach ($values as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);
        $query.=" ORDER BY datetime DESC LIMIT 1";

        $state = $this->dbconn->query(trim($query));
        if (!$state) {
            return $this->dbconn->error;
        }
        return $state->fetch_assoc();
    }

    /**
     * Select with Where conditions
     * @param type $values
     * @return type
     */
    function select($values) {
        $query = "select * from " . $this->table . " where ";
        foreach ($values as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);
        $result = $this->dbconn->query(trim($query));

        return $result;
    }

	// add room function

    /**
     * Delete with Where conditions
     * @param type $values
     * @return type
     */
    function delete($values) {
        $query = "delete from " . $this->table . " where ";
        foreach ($values as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);

        $result = $this->dbconn->query(trim($query));

        if (!$result) {
            return $this->dbconn->error;
        }

        return $this->dbconn->affected_rows;
    }

    /**
     * Update query that take 2 arrays one for set and the other for where conditions
     * @param type $set
     * @param type $where
     * @return type
     */
    function update($where, $set) {
        $query = "update " . $this->table . " set ";
        foreach ($set as $key => $value) {
            $query.=$key . " = '" . $value . "' , ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);

        $query.=" where ";

        foreach ($where as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);
        $result = $this->dbconn->query(trim($query));

        if (!$result) {
            return $this->dbconn->error;
        }

        return $this->dbconn->affected_rows;
    }

    /**
     * select between two dates with where conditions
     */
    function select_date($datecol, $date_from, $date_to, $values) {
        $query = "select * from " . $this->table . " where( " . $datecol . " between '" . $date_from . "' and '" . $date_to . "') and ";
        foreach ($values as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);

        $result = $this->dbconn->query(trim($query));

        return $result;
    }

    /**
     * select query to get sumtion of specific colum and group by specific colum by where conditions
     * between specific date
     */
    function select_sum($col_sum, $values, $col_group_by, $datecol, $date_from, $date_to) {
        $query = "select sum(" . $col_sum . ") from " . $this->table . " where( " . $datecol . " between '" . $date_from . "' and '" . $date_to . "') and ";
        foreach ($values as $key => $value) {
            $query.=$key . " = '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);

        $query.=" group by " . $col_group_by;

        $result = $this->dbconn->query(trim($query));

        return $result;
    }


    /**
     * select all sorted decreasing by where not equal
     */

    
    function select_all_sorted($col_sorted,$values) {
        $query = "SELECT * FROM " . $this->table. " where ";
        foreach ($values as $key => $value) {
            $query.=$key . " != '" . $value . "' and ";
        }
        $query = explode(" ", $query);
        unset($query[count($query) - 2]);
        $query = implode(" ", $query);
        $query.=" ORDER BY ".$col_sorted." DESC";
        $result = $this->dbconn->query(trim($query));

        return $result;
    }


    


    /**
     * deconstractor to close connection
     */
    function __destruct() {
        mysqli_close($this->dbconn);
    }

}
