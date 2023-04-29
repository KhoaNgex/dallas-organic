<?php

trait Database
{ 
 
	private function connect()
	{
		$servername = DBHOST;
		$serverport = DBPORT;
		$databasename = DBNAME;
		$string = "mysql:host=$servername;dbname=$databasename;port=$serverport";
		$con = new PDO($string, DBUSER, DBPASS);
		return $con;
	}
 
	public function query($query, $data = [])
	{
		$con = $this->connect();
		$stm = $con->prepare($query);
		# prevent sql injection
		if (str_contains($query, "insert") || str_contains($query, "update") || str_contains($query, "delete")) {
			foreach ($data as $key => $value) {
				$value = esc(strip_tags(($value)));
			}
			$check = $stm->execute($data);
			return $check;
		} else {
			$check = $stm->execute($data);
			$result = $stm->fetchAll(PDO::FETCH_OBJ);
			if (is_array($result) && count($result)) {
				return $result;
			}
			return false;
		}
	}

	public function get_row($query, $data = [])
	{
		$con = $this->connect();
		$stm = $con->prepare($query);

		$check = $stm->execute($data);
		if ($check) {
			$result = $stm->fetchAll(PDO::FETCH_OBJ);
			if (is_array($result) && count($result)) {
				return $result[0];
			}
		}

		return false;
	}

}