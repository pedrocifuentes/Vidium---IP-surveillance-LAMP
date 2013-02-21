<?php

class DataBase {

    private $conexion;
    private $resource;
    private $sql;
    public static $queries;
    private static $_singleton;

    public static function getInstance(){
        if (is_null (self::$_singleton)) {
            self::$_singleton = new DataBase();
        }
        return self::$_singleton;
    }

    private function __construct(){
        $this->conexion = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
        if (!$this->conexion){
            die("Fallo en la conexion con la base de datos".mysql_error());
        }
        mysql_select_db(DB_NAME, $this->conexion);
        $this->queries = 0;
        $this->resource = null;
    }

    public function execute(){
        if(!($this->resource = mysql_query($this->sql, $this->conexion))){
            die("DATABASE.CLASS: Fallo en la ejecución de la siguiente consulta sql: ".$this->sql);
        }
        $this->queries++;
        return $this->resource;
    }

    public function alter(){
        if(!($this->resource = mysql_query($this->sql, $this->conexion))){
            return false;
        }
        return true;
    }

    public function loadObjectList(){
        if (!($cur = $this->execute())){
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_object($cur)){
            $array[] = $row;
        }
        return $array;
    }

    public function getObjectSql($sql){
        return mysql_fetch_object($sql);
    }

    public function setQuery($sql){
        if(empty($sql)){
            return false;
        }
        $this->sql = $sql;
        return true;
    }

    public function freeResults(){
        @mysql_free_result($this->resource);
        return true;
    }

    public function loadObject(){
        if ($cur = $this->execute()){
            if ($object = mysql_fetch_object($cur)){
                @mysql_free_result($cur);
                return $object;
            }
            else {
                return null;
            }
        }
        else {
            return false;
        }
    }
  
    public function __destruct(){
    	if(@get_resource_type($this->resource))
        	@mysql_free_result($this->resource);
        mysql_close($this->conexion);
    }
    
    public function loadArrayList(){
        if (!($cur = $this->execute())){
            return null;
        }
        $array = array();
        while ($row = mysql_fetch_assoc($cur)){
            $array[] = $row;
        }
        return $array;
    }

    public function loadArray(){
        if ($cur = $this->execute()){
            if ($object = mysql_fetch_assoc($cur)){
                @mysql_free_result($cur);
                return $object;
            }
            else {
                return null;
            }
        }
        else {
            return false;
        }
    }    
}