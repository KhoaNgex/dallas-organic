<?php

class Order
{
	use Model;

	protected $table = 'orders';

	public function insert($data)
	{
		$values_array = array_values($data);
		$query = "call placeOrder($values_array);";
		return $this->query($query);
	}
}