<?php

require_once __DIR__ . "/../config.php";

class DB {

    private $o;

    public function __construct(){
        
        $this->connect();

    }

    private function connect(){

		try {
			$this->dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
		} catch (PDOException $e) {
			die("Es konnte keine Verbindung zur Datenbank hergestellt werden.");
		}

	}
	
    public function get($sql, $e = []){
        try{
			$res = $this->query($sql, $e);
            if($res) return $res->fetch();
            else return false;
        }catch(Exception $e){
            return false;
        }
	}
	
	public function query($sql, $e = []){
		$s = $this->dbh->prepare($sql);
		$s->execute($e);
		return $s;
	}
	
	public function count ($sql, $e = []) {
		$s = $this->query($sql, $e);
		return $s->fetchColumn();
	}
	
    public function set($sql, $e = []){
		$s = $this->dbh->prepare($sql);
		return $s->execute($e);
    }


}

$db = new DB();