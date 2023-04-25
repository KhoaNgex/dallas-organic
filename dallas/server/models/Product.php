<?php
class Product
{
	use Model;
	protected $table = 'products';

	public function getBestSellers()
	{
		$query = "call getBestSellers();";
		return $this->query($query);
	}

}