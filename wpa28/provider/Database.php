<?php
/**
* Database Libary for wpa28
* 1. Select by columns name;
* 2. Select All;
* 3.
*/
class DB extends PDO {

    private $engine;
    private $host;
    private $database;
    private $user;
    private $pass;

    private static $_instance;

    private $table_name;
    private $sql;
    private $select_status;

    public function __construct() {
        // echo "DB Construct! <br>";
        $this->engine = Config::get('database.engine');
        $this->host = Config::get('database.host');
        $this->database = Config::get('database.database');
        $this->user = Config::get('database.user');
        $this->pass = Config::get('database.pass');
        // mysql:dbname=wpa28db;host=localhost
        $dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
        // var_dump($dns);
        try {
            $conn = parent::__construct( $dns, $this->user, $this->pass );
        } catch (PDOException $e) {
            echo "Something wrong. Database connection failed.";
        }

    }

    public static function table($table_name)
    {
        if (!self::$_instance instanceof DB) {
            self::$_instance = new DB();
        }

        self::$_instance->table_name = $table_name;
        self::$_instance->select_status = false;
        self::$_instance->sql = '';

        return self::$_instance;
    }

    // Select / Get
    public function getAll() {
        $sql = "SELECT * FROM " . $this->table_name . $this->where;
        $this->where = "";
        // var_dump($sql);
        try {

            $prep = $this->prepare($sql);

            if ($prep) {
                $state = $prep->execute();
            }

            if ($state) {
                $result = $prep->fetchAll(PDO::FETCH_ASSOC);
            }
            else {
                $error = $prep->errorInfo();
                echo "Failed to perform the query with message : ". $error[2];
            }

        } catch (PDOException $e) {
            echo "A database problem has occured : " . $e->getMessage();
        }

        return $result;
    }

    // Where
    public function where($id, $value) {
        $valuetype = gettype($value);
        // $this->sql = "SELECT * FROM " . $this->table_name . " WHERE " . $id . " = ";
        $this->where = " WHERE " . $id . " = ";
        if($valuetype == "string") {
            $this->where .= "'" . $value . "'";
        } else {
            $this->where .= $value;
        }
        return $this;
    }

    // Delete
    public function delete() {
        $sql = "DELETE FROM " . $this->table_name . $this->where;
        $this->where = "";
        // var_dump($sql);
        $prep = $this->prepare($sql);
        $result = $prep->execute();
        if($result == true) {
            echo "Delete Successfully!";
        }
    }


    // Select by col-name
    public function select(string ...$col) {
        $this->sql_status = true;
    }

    // Insert
    public function insert($data) {
        $keys = array_keys($data);
        $col_name = implode(", ", $keys);
        $values = array_values($data);
        $pre_val = "";
        foreach ($values as $val) {
            $pre_val .= "'" . $val . "', ";
        }
        $insert_values = rtrim($pre_val, ", ");

        $sql = "INSERT INTO " . $this->table_name . " (" . $col_name . ") VALUES " . " (" . $insert_values . ")";
        // var_dump($sql);
        $prep = $this->prepare($sql);
        $result = $prep->execute();
        if($result == true) {
            echo "Inserted data into students tables.";
        }
    }

    // Update
    public function update($data) {
        $pre_data = "";
        foreach ($data as $key => $value) {
            $pre_data .= $key . " = ";
            if(gettype($value) == "string") {
                $pre_data .= "'" . $value . "', ";
            }
            else {
                $pre_data .= $value . ", ";
            }
        }

        $update_data = rtrim($pre_data, ", ");

        $sql = "UPDATE " . $this->table_name . " SET " . $update_data . $this->where;
        $this->where = "";
        // var_dump($sql);
        $prep = $this->prepare($sql);
        $result = $prep->execute();
        if($result == true) {
            echo "Updated Successfully <br>";
        }
    }



    /**
    * Destructor
    */
    public function __destruct() {
        echo "<br> DB Destructed! <br>";
    }
}

?>
