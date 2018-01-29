<?php

    /**
    * Select by column name
    */
    public function select(string ...$col) {
        $this->select_status = true;
        $columns = implode(", ", $col);
        $this->sql = "SELECT " . $columns . " FROM " . $this->table_name;

        return $this;
    }

    /**
    * WHERE
    */
    public function where() {
        $arg = func_get_args();
        if (gettype(end($arg)) == "string") {
            $val = "'" . end($arg) . "'";
        } else {
            $val = end($arg);
        }
        array_pop($arg);
        $where_clause = implode(" ", $arg);
        var_dump($this->sql);
        if (!$this->sql) {
            $this->sql = "SELECT * FROM " . $this->table_name;
        }
        $this->sql .= " WHERE " . $where_clause . " $val";
        var_dump($this->sql);
        return $this;
    }

    /**
    * Get all
    */
    public function get() {
        if (!$this->select_status) {
            $this->sql = "SELECT * FROM " . $this->table_name;
        }
        // var_dump($this->sql);die();
        $statement = $this->prepare($this->sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


 ?>
