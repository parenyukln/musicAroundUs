<?php 

class DataBase {
 	private $mysqli;
  	private $db_info = [
        'host' => 'localhost',
        'db_name' => 'cl18599_music',
        'user' => 'cl18599_music',
        'pass' => 'K3NpWsRj'
    ];
  
  	public function __construct() {
      $this->mysqli = new mysqli($this->db_info['host'], $this->db_info['user'], $this->db_info['pass'], $this->db_info['db_name']);
      $this->mysqli->query("SET lc_time_names = 'ru_RU'");
      $this->mysqli->query("SET NAMES 'utf8'");
  	}
  
  	public function executeQuery($query) {
      $result = $this->mysqli->query($query) or die (mysql_error());
      return $result;
    }
  
  	public function checkToken($token) {
    	$token_query = $this->executeQuery("SELECT id FROM users WHERE token='".$token."'");
      	return $token_query;
    }
    
    public function getInsertedId() {
    	$inserted_id =  $this->mysqli->insert_id;
    	return $inserted_id;
    }
  
  	public function __destruct() {
      if ($this->mysqli) $this->mysqli->close();
    }
}