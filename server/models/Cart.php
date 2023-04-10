<?php

class Cart
{

	use Model;

	protected $table = 'cart';

	public function findAll($user_id)
	{
		$query = "call getAllProductInCart($user_id);";
		return $this->query($query);
	}

}