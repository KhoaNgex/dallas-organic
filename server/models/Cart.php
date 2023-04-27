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

	public function updateQuantity($data)
	{
		$userID = $data["userID"];
		$productID = $data["productID"];
		$quantity = $data["quantity"];
		$query = "call updateQuantityInCart($userID, $productID, $quantity);";
		return $this->query($query);
	}

	public function insert($data)
	{
		$userID = $data["userID"];
		$productID = $data["productID"];
		$quantity = $data["quantity"];
		$query = "call insertProductToCart($userID, $productID, $quantity);";
		return $this->query($query);
	}

	public function deleteItem($data)
	{
		$userID = $data["userID"];
		$productID = $data["productID"] != "" ? $data["productID"] : -1;
		$query = "call deleteItemInCart($userID, $productID);";
		return $this->query($query);
	}

}