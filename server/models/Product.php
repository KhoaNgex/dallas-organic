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

	public function findAllTitle($data)
	{
		$offset = $data['offset'];
		$sortorder = $data['so'];
		$sortfield = $data['sf'];
		$price = $data['price'];
		$cate = $data['cate'];
		$name = $data['name'];
		$query = "call getAllProductTitle($offset,$sortorder,$sortfield,$price,$cate,$name);";
		return $this->query($query);
	}

	public function findCount()
	{
		$query = "select count_products();";
		return $this->query($query);
	}
}