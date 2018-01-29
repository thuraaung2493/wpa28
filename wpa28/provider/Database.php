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
    private $where;

    public function __construct() {
        // echo "DB Construct! <br>";
        $this->engine = Config::get('database.engine');
        $this->host = Config::get('database.host');
        $this->database = Config::get('database.database');
        $this->user = Config::get('database.user');
        $this->pass = Config::get('database.pass');
        // mysql:dbname=wpa28db;host=localhost
        $dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
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
        self::$_instance->where = '';

        return self::$_instance;
    }

    // Select
    public function select(string ...$col) {
        $this->select_status = true;
        $columns = implode(", ", $col);
        // var_dump($columns);die();
        $this->sql = "SELECT " . $columns . " FROM " . $this->table_name;

        return $this;
    }

    // Where
    public function where() {
        $arg = func_get_args();
        if (gettype(end($arg)) == "string") {
            $val = "'" . end($arg) . "'";
        } else {
            $val = end($arg);
        }
        array_pop($arg);
        var_dump($arg);
        $where_clause = implode(" ", $arg);
        $this->sql .= " WHERE " . $where_clause . " $val";
        return $this;
    }

    // Select All
    public function get() {
        if (!$this->select_status) {
            $this->sql = "SELECT * FROM " . $this->table_name;
        }
        // var_dump($this->sql);die();
        $prep = $this->prepare($this->sql);
        var_dump($prep);
        $prep->execute();
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);

        return $result;
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



    /**
    * Destructor
    */
    public function __destruct() {
        echo "<br> DB Destructed! <br>";
    }
}

?>
