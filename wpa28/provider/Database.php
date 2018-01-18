<?php

class DB extends PDO {

    private static $_instance;

    private $table_name;
    private $where;

    private $engine;
    private $host;
    private $database;
    private $user;
    private $pass;

    public function __construct() {
        echo "DB Construct! <br>";
        $this->engine = 'mysql';
        $this->host = 'localhost';
        $this->database = 'wpa28db';
        $this->user = 'root';
        $this->pass = '1234';
        // mysql:dbname=wpa28db;host=localhost
        $dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
        // var_dump($dns);
        try {
            $conn = parent::__construct( $dns, $this->user, $this->pass );
        } catch (PDOException $e) {
            echo "Something wrong. Database connection failed.";
        }

    }

    public function __destruct() {
        echo "<br> DB Destructed! <br>";
    }

    public static function table($table_name) {
        if(!self::$_instance instanceof DB) {
            self::$_instance = new DB();
        }
        self::$_instance->table_name = $table_name;
        return self::$_instance;
    }

    // Select / Get
    public function get() {
        $sql = "SELECT * FROM " . $this->table_name . $this->where;
        $this->where = "";
        // var_dump($sql);
        $prep = $this->prepare($sql);
        $prep->execute();
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);
        if($result == false) {
            trigger_error("Table not found", E_USER_ERROR);
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
}

?>