<?php

class Config {
    public function get($config)
    {
        $e_config = explode(".", $config);
        $configs = require DD . '/app/config/' . $e_config[0] . ".php";
        return $configs[$e_config[1]];
    }
}

 ?>
