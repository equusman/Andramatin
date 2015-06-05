<?php
final class XMySQLi {
	private $mysqli;
	
	public function __construct($hostname, $username, $password, $database, $timezone) {
		$this->mysqli = mysqli_connect($hostname, $username, $password, $database);
		
		if ($this->mysqli->connect_error) {
      		trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}
		
		$this->mysqli->query("SET NAMES 'utf8'");
		$this->mysqli->query("SET CHARACTER SET utf8");
		$this->mysqli->query("SET CHARACTER_SET_CONNECTION=utf8");
		$this->mysqli->query("SET SQL_MODE = ''");
		$this->mysqli->query("SET TIME_ZONE = '".$timezone."'");
  	}
		
  	public function rawquery($sql) {
		return $this->mysqli->query($sql);
	}
	
	public function query($sql) {
		$result = $this->mysqli->query($sql);

		if (!$this->mysqli->errno) {
			// echo $sql.'<br/>';	
			// print_r($result);
			// echo '================<br/>';
			if (is_object($result)) {
				$i = 0;
		
				$data = array();
		
				while ($row = $result->fetch_array()) {
					$data[$i] = $row;
		
					$i++;
				}

				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $result->num_rows;

				$result->close();
				
				unset($data);
				
				
				
				
				return $query;	
			} else {
				return $result;
				
			}
		} else {
			trigger_error('Error: ' . mysqli_error($this->mysqli) . '<br />Error No: ' . mysqli_errno($this->mysqli) . '<br />' . $sql);
			exit();
    	}
		
  	}
	
	public function escape($value) {
		return $this->mysqli->real_escape_string($value);
	}
	
  	public function countAffected() {
    	return $this->mysqli->affected_rows;
  	}

  	public function getLastId() {
    	return $this->mysqli->insert_id;
  	}	
	
	public function __destruct() {
		$this->mysqli->close();
	}
}
?>