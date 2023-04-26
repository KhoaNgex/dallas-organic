<?php

class User
{
	use Model;

	protected $table = 'user_account';

	protected $allowedColumns = ['id', 'username', 'fullname', 'sex', 'DoB', 'phonenumber', 'email', 'address', 'avatar'];

	public function findAll()
	{
		$query = "select " . implode(',', $this->allowedColumns) . " from " . $this->table . " order by " . $this->order_column . " " . $this->order_type;
		return $this->query($query);
	}
	public function where($data, $data_not = [])
	{
		$keys = array_keys($data);
		$keys_not = array_keys($data_not);
		$query = "select " . implode(',', $this->allowedColumns) . " from $this->table where ";

		foreach ($keys as $key) {
			$query .= $key . " = :" . $key . " && ";
		}

		foreach ($keys_not as $key) {
			$query .= $key . " != :" . $key . " && ";
		}

		$query = trim($query, " && ");

		$query .= " order by $this->order_column $this->order_type";
		$data = array_merge($data, $data_not);

		return $this->query($query, $data);
	}
}